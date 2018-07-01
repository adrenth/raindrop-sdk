<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\RegisterUserFailed;
use Adrenth\Raindrop\Exception\UnregisterUserFailed;
use Adrenth\Raindrop\Exception\VerifySignatureFailed;
use Adrenth\Raindrop\Response\VerifySignatureResponse;
use Adrenth\Raindrop\TokenStorage\TokenStorageInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Client
 *
 * Client-side Raindrop is a multi-factor authentication (MFA) solution for client login portals with many end-users
 * frequently requesting access to their respective accounts.
 *
 * @package Adrenth\Raindrop
 */
class Client extends ApiBase
{
    /**
     * Your unique application ID
     *
     * @var string
     */
    private $applicationId;

    /**
     * @param ApiSettings $settings
     * @param TokenStorageInterface $tokenStorage
     * @param string $applicationId
     */
    public function __construct(
        ApiSettings $settings,
        TokenStorageInterface $tokenStorage,
        string $applicationId
    ) {
        parent::__construct($settings, $tokenStorage);

        $this->applicationId = $applicationId;
    }

    /**
     * @param string $hydroId
     * @return void
     * @throws RegisterUserFailed
     */
    public function registerUser(string $hydroId): void
    {
        try {
            $response = $this->callHydroApi(
                'post',
                'application/client',
                [
                    'json' => [
                        'application_id' => $this->applicationId,
                        'hydro_id' => $hydroId
                    ]
                ]
            );
        } catch (GuzzleException $e) {
            throw RegisterUserFailed::withHydroId($hydroId, $e->getMessage(), $e);
        }

        if ($response->getStatusCode() !== 201) {
            throw RegisterUserFailed::withHydroId(
                $hydroId,
                sprintf('Invalid response code %s from server, expected 201.', $response->getStatusCode())
            );
        }
    }

    /**
     * @param string $hydroId
     * @throws UnregisterUserFailed
     */
    public function unregisterUser(string $hydroId): void
    {
        try {
            $response = $this->callHydroApi(
                'delete',
                sprintf(
                    'application/client?hydro_id=%s&application_id=%s',
                    $hydroId,
                    $this->applicationId
                )
            );
        } catch (GuzzleException $e) {
            throw UnregisterUserFailed::withHydroId($hydroId, $e->getMessage(), $e);
        }

        if ($response->getStatusCode() !== 204) {
            throw UnregisterUserFailed::withHydroId(
                $hydroId,
                sprintf('Invalid response code %s from server, expected 204.', $response->getStatusCode())
            );
        }
    }

    /**
     * @return int
     * @throws Exception If it was not possible to gather sufficient entropy.
     */
    public function generateMessage(): int
    {
        return random_int(100000, 999999);
    }

    /**
     * @param string $hydroId
     * @param int $message
     * @return VerifySignatureResponse
     * @throws VerifySignatureFailed
     */
    public function verifySignature(string $hydroId, int $message): VerifySignatureResponse
    {
        try {
            $response = $this->callHydroApi(
                'get',
                sprintf(
                    'verify_signature?message=%d&hydro_id=%s&application_id=%s',
                    $message,
                    $hydroId,
                    $this->applicationId
                )
            );
            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (RuntimeException | InvalidArgumentException | GuzzleException $e) {
            throw VerifySignatureFailed::withHydroId($hydroId, $e->getMessage(), $e);
        }

        return new VerifySignatureResponse(
            $data['verification_id'],
            strtotime($data['timestamp'])
        );
    }
}

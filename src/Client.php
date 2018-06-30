<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\RegisterUserFailed;
use Adrenth\Raindrop\Exception\UnregisterUserFailed;
use Adrenth\Raindrop\Exception\VerifySignatureFailed;
use Adrenth\Raindrop\TokenStorage\TokenStorageInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Client
 *
 * Client-side Raindrop is a multi-factor authentication (MFA) solution for client login portals with many end-users
 * frequently requesting access to their respective accounts.
 *
 * @package Adrenth\Raindrop
 */
class Client extends Base
{
    /**
     * Your unique application ID
     *
     * @var string
     */
    private $applicationId;

    /**
     * @param Settings $settings
     * @param TokenStorageInterface $tokenStorage
     * @param string $applicationId
     */
    public function __construct(Settings $settings, TokenStorageInterface $tokenStorage, string $applicationId)
    {
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
            $response = $this->callHydroApi('post', '/application/client', [
                'form_params' => [
                    'application_id' => $this->applicationId,
                    'hydro_id' => $hydroId,
                ]
            ]);
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
                    '/application/client?hydro_id=%s&application_id=%s',
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
     * @throws VerifySignatureFailed
     */
    public function verifySignature(string $hydroId, int $message)
    {
        try {
            $response = $this->callHydroApi(
                'get',
                sprintf(
                    '/application/client?message=%d&hydro_id=%s&application_id=%s',
                    $message,
                    $hydroId,
                    $this->applicationId
                )
            );
        } catch (GuzzleException $e) {
            throw VerifySignatureFailed::withHydroId($hydroId, $e->getMessage(), $e);
        }

        try {
            $json = $response->getBody()->getContents();

            $data = \GuzzleHttp\json_decode($json, true);
        } catch (\RuntimeException | \InvalidArgumentException $e) {
            throw VerifySignatureFailed::withHydroId($hydroId, $e->getMessage(), $e);
        }

        // TODO: Handle response data.
    }
}

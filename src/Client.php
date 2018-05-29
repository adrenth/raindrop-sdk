<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\InvalidResponse;
use GuzzleHttp\ClientInterface;
use RuntimeException;

/**
 * Class Client
 *
 * @package Adrenth\Raindrop
 */
class Client
{
    /**
     * API Username.
     *
     * @var string
     */
    protected $username;

    /**
     * API Key.
     *
     * @var string
     */
    protected $key;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @param string $baseUri
     * @param string $username
     * @param string $key
     */
    public function __construct(string $baseUri, string $username, string $key)
    {
        $this->username = $username;
        $this->key = $key;
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'adrenth.raindrop-sdk/1.0'
            ]
        ]);
    }

    /**
     * Whitelist Hydro Address.
     *
     * @param string $address
     * @return string Hydro Address Identifier
     * @throws InvalidResponse
     */
    public function whitelist(string $address): string
    {
        $response = $this->httpClient->post(
            sprintf('/whitelist/%s', $address),
            [
                'form_params' => [
                    'username' => $this->username,
                    'key' => $this->key
                ]
            ]
        );

        try {
            $data = \GuzzleHttp\json_encode($response->getBody()->getContents(), true);
        } catch (RuntimeException $e) {
            throw new InvalidResponse(sprintf(
                'Could not read response from server while whitelisting address %s: %s',
                $address,
                $e->getMessage()
            ));
        }

        if (!is_array($data) || !isset($data['hydro_address_id']) || empty($data['hydro_address_id'])) {
            throw new InvalidResponse(sprintf(
                'Could not whitelist Hydro address %s due to an invalid server response.',
                $address
            ));
        }

        return $data['hydro_address_id'];
    }

    /**
     * @param int $amount
     * @param int $partnerId
     * @param string $challenge
     */
    public function challenge(int $amount, int $partnerId, string $challenge)
    {
        // TODO
    }

    /**
     * @param string $hydroAddresssId
     */
    public function authenticate(string $hydroAddresssId)
    {
        // TODO
    }
}

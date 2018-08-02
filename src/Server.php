<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\AddressWhitelistingFailed;
use Adrenth\Raindrop\Exception\ApiRequestFailed;
use Adrenth\Raindrop\Exception\AuthenticationFailed;
use Adrenth\Raindrop\Exception\ChallengeFailed;
use Adrenth\Raindrop\Response\AuthenticationResponse;
use Adrenth\Raindrop\Response\ChallengeResponse;
use Adrenth\Raindrop\Response\WhitelistResponse;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Server
 *
 * Server-side Raindrop is meant to secure access to large systems, databases, and APIs. Server-side Raindrop consists
 * of a transaction performed via a smart contract that publicly validates access to a private system.
 *
 * @package Adrenth\Raindrop
 */
class Server extends ApiBase
{
    /**
     * All users who want to access to your Raindrop-enabled system will have to be whitelisted.
     *
     * @param string $address Hydro Etherium Address
     * @return WhitelistResponse
     * @throws AddressWhitelistingFailed
     */
    public function whitelist(string $address): WhitelistResponse
    {
        try {
            $response = $this->callHydroApi(
                'post',
                'whitelist',
                [
                    'timeout' => 60,
                    'json' => [
                        'address' => $address
                    ]
                ]
            );

            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (RuntimeException $e) {
            throw AddressWhitelistingFailed::forAddress($address, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw AddressWhitelistingFailed::forAddress($address, $e->getMessage());
        } catch (GuzzleException $e) {
            throw AddressWhitelistingFailed::forAddress($address, $e->getMessage());
        } catch (ApiRequestFailed $e) {
            throw AddressWhitelistingFailed::forAddress($address, $e->getMessage());
        }

        return new WhitelistResponse(
            $data['hydro_address_id'],
            $data['transaction_hash']
        );
    }

    /**
     * After being whitelisted, each user must authenticate through the Server-side Raindrop process once every 24 hours
     * to retain access to the protected system.
     *
     * @param string $hydroAddressId
     * @return ChallengeResponse
     * @throws ChallengeFailed
     */
    public function challenge(string $hydroAddressId): ChallengeResponse
    {
        try {
            $response = $this->callHydroApi(
                'post',
                'challenge',
                [
                    'json' => [
                        'hydro_address_id' => $hydroAddressId
                    ]
                ]
            );

            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true, 512, JSON_BIGINT_AS_STRING);
        } catch (RuntimeException $e) {
            throw ChallengeFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw ChallengeFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (GuzzleException $e) {
            throw ChallengeFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (ApiRequestFailed $e) {
            throw ChallengeFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        }

        return new ChallengeResponse(
            $data['amount'],
            (int) $data['challenge'],
            (int) $data['partner_id'],
            $data['transaction_hash']
        );
    }

    /**
     * @param string $hydroAddressId
     * @return AuthenticationResponse
     * @throws AuthenticationFailed
     */
    public function authenticate(string $hydroAddressId): AuthenticationResponse
    {
        try {
            $response = $this->callHydroApi(
                'get',
                'authenticate?hydro_address_id=' . $hydroAddressId
            );

            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (RuntimeException $e) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (GuzzleException $e) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        } catch (ApiRequestFailed $e) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, 'Unexpected response code.');
        }

        return new AuthenticationResponse(
            $data['authentication_id'],
            strtotime($data['timestamp'])
        );
    }
}

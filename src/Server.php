<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\AddressWhitelistingFailed;
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
     * @param string $address Hydro Etherium Address
     * @return WhitelistResponse
     * @throws AddressWhitelistingFailed
     */
    public function whitelist(string $address): WhitelistResponse
    {
        try {
            $response = $this->callHydroApi(
                'post',
                '/whitelist/' . $address,
                [
                    'timeout' => 60
                ]
            );
            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw AddressWhitelistingFailed::forAddress(
                $address,
                $e->getMessage()
            );
        }

        return new WhitelistResponse(
            $data['hydro_address_id'],
            $data['transaction_hash']
        );
    }

    /**
     * @param string $hydroAddressId
     * @return ChallengeResponse
     * @throws ChallengeFailed
     */
    public function challenge(string $hydroAddressId): ChallengeResponse
    {
        try {
            $response = $this->callHydroApi(
                'post',
                '/challenge',
                [
                    'form_params' => [
                        'hydro_address_id' => $hydroAddressId
                    ]
                ]
            );

            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (RuntimeException | InvalidArgumentException | GuzzleException $e) {
            throw ChallengeFailed::forHydroAddressId(
                $hydroAddressId,
                $e->getMessage()
            );
        }

        return new ChallengeResponse(
            (int) $data['amount'],
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
                '/authenticate?hydro_address_id=' . $hydroAddressId
            );

            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (RuntimeException | InvalidArgumentException | GuzzleException $e) {
            throw AuthenticationFailed::forHydroAddressId(
                $hydroAddressId,
                $e->getMessage()
            );
        }

        if ($response !== 200) {
            throw AuthenticationFailed::forHydroAddressId($hydroAddressId, 'Unexpected response code.');
        }

        return new AuthenticationResponse(
            $data['authentication_id'],
            strtotime($data['timestamp'])
        );
    }
}

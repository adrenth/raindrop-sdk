<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Exception\UnableToAcquireAccessToken;
use Adrenth\Raindrop\Exception\ApiRequestFailed;
use Adrenth\Raindrop\Exception\RefreshTokenFailed;
use Adrenth\Raindrop\TokenStorage\TokenStorage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class ApiBase
 *
 * @package Adrenth\Raindrop
 */
abstract class ApiBase
{
    const USER_AGENT = 'adrenth.raindrop-sdk (PHP) version 1.2.0';

    /**
     * Settings which are required to connect to the API.
     *
     * @var ApiSettings
     */
    private $settings;

    /**
     * The storage for the API authentication token.
     *
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * HTTP Client for making API calls.
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @param ApiSettings $settings
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        ApiSettings $settings,
        TokenStorage $tokenStorage
    ) {
        $this->settings = $settings;
        $this->tokenStorage = $tokenStorage;
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $this->settings->getEnvironment()->getApiUrl() . '/hydro/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => self::USER_AGENT
            ]
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws ApiRequestFailed
     */
    protected function callHydroApi(string $method, string $uri, array $options = []): ResponseInterface
    {
        if (!isset($options['handler'])) {
            $options['handler'] = $this->getHandlerStack();
        }

        return $this->httpClient->request($method, $uri, $options);
    }

    /**
     * @return ApiAccessToken
     * @throws RefreshTokenFailed
     */
    protected function refreshToken(): ApiAccessToken
    {
        try {
            $client = new \GuzzleHttp\Client([
                'base_uri' => $this->settings->getEnvironment()->getApiUrl(),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => self::USER_AGENT,
                    'Authorization' => 'Basic ' . base64_encode(
                        $this->settings->getClientId() . ':' . $this->settings->getClientSecret()
                    )
                ]
            ]);

            $response = $client->post('/authorization/v1/oauth/token?grant_type=client_credentials');
        } catch (RequestException $e) {
            throw new RefreshTokenFailed($e->getMessage());
        }

        try {
            $json = $response->getBody()->getContents();

            $data = \GuzzleHttp\json_decode($json, true);
        } catch (RuntimeException $e) {
            throw new RefreshTokenFailed('Invalid response from server');
        } catch (InvalidArgumentException $e) {
            throw new RefreshTokenFailed('Invalid response from server');
        }

        $accessToken = new ApiAccessToken($data['access_token'], time() + $data['expires_in']);

        $this->tokenStorage->setAccessToken($accessToken);

        return $accessToken;
    }

    /**
     * @return HandlerStack
     */
    private function getHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $token = $this->getAccessToken();

            if ($token) {
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return $request->withHeader('Authorization', "Bearer {$token->getToken()}");
            }

            return $request;
        }), 'add_oauth2_header');

        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                /** @var PromiseInterface $promise */
                $promise = $handler($request, $options);

                return $promise->then(
                    function (ResponseInterface $response) {
                        $statusCode = $response->getStatusCode();

                        if ($response->getStatusCode() === 500) {
                            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                            throw ApiRequestFailed::withCode(ApiRequestFailed::INTERNAL_SERVER_ERROR, $response);
                        }

                        $contents = null;

                        try {
                            $contents = $response->getBody()->getContents();

                            if (!empty($contents)) {
                                $contents = \GuzzleHttp\json_decode($contents, true);
                            }
                        } catch (RuntimeException $e) {
                            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                            throw ApiRequestFailed::withCode(ApiRequestFailed::INVALID_JSON, $response);
                        } catch (InvalidArgumentException $e) {
                            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                            throw ApiRequestFailed::withCode(ApiRequestFailed::INVALID_JSON, $response);
                        }

                        if (is_array($contents)
                            && array_key_exists('message', $contents)
                            && ($statusCode >= 400 && $statusCode < 500)
                        ) {
                            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                            throw ApiRequestFailed::withMessage($contents['message'], $response);
                        }

                        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                        $response->getBody()->rewind();
                        return $response;
                    }
                );
            };
        });

        return $stack;
    }

    /**
     * Get the API access token. This method can be used to verify if provided `ApiSettings` are valid.
     *
     * @return ApiAccessToken
     * @throws RefreshTokenFailed
     */
    public function getAccessToken(): ApiAccessToken
    {
        try {
            $accessToken = $this->tokenStorage->getAccessToken();
        } catch (UnableToAcquireAccessToken $e) {
            $accessToken = null;
        }

        if ($accessToken && $accessToken->isExpired()) {
            $this->tokenStorage->unsetAccessToken();
            $accessToken = null;
        }

        return $accessToken ?: $this->refreshToken();
    }
}

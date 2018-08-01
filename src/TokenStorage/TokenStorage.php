<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;
use Adrenth\Raindrop\Exception\UnableToAcquireAccessToken;

/**
 * Interface TokenStorage
 *
 * @package Adrenth\Raindrop\TokenStorage
 */
interface TokenStorage
{
    /**
     * @return ApiAccessToken
     * @throws UnableToAcquireAccessToken
     */
    public function getAccessToken(): ApiAccessToken;

    /**
     * @param ApiAccessToken $token
     * @return void
     */
    public function setAccessToken(ApiAccessToken $token);

    /**
     * @return void
     */
    public function unsetAccessToken();
}

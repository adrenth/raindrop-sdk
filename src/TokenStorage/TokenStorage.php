<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;

/**
 * Interface TokenStorage
 *
 * @package Adrenth\Raindrop\TokenStorage
 */
interface TokenStorage
{
    /**
     * @return ApiAccessToken|null
     */
    public function getAccessToken(): ?ApiAccessToken;

    /**
     * @param ApiAccessToken $token
     * @return void
     */
    public function setAccessToken(ApiAccessToken $token): void;

    /**
     * @return void
     */
    public function unsetAccessToken(): void;
}

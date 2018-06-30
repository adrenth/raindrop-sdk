<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;

/**
 * Interface TokenStorageInterface
 *
 * @package Adrenth\Raindrop\TokenStorage
 */
interface TokenStorageInterface
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

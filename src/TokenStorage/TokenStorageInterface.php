<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\AccessToken;

/**
 * Interface TokenStorageInterface
 *
 * @package Adrenth\Raindrop\TokenStorage
 */
interface TokenStorageInterface
{
    /**
     * @return AccessToken|null
     */
    public function getAccessToken(): ?AccessToken;

    /**
     * @param AccessToken $token
     * @return void
     */
    public function setAccessToken(AccessToken $token): void;

    /**
     * @return void
     */
    public function unsetAccessToken(): void;
}

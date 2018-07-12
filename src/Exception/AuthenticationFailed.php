<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

/**
 * Class AuthenticationFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class AuthenticationFailed extends Base
{
    /**
     * @param string $hydroAddressId
     * @param string $message
     * @return AuthenticationFailed
     */
    public static function forHydroAddressId(string $hydroAddressId, string $message): AuthenticationFailed
    {
        return new static('Could not authenticate ' . $hydroAddressId . ': ' . $message);
    }
}

<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

/**
 * Class AddressWhitelistingFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class AddressWhitelistingFailed extends Base
{
    /**
     * @param string $address
     * @param string $message
     * @return AddressWhitelistingFailed
     */
    public static function forAddress(string $address, string $message): AddressWhitelistingFailed
    {
        return new static('Could not whitelist address ' . $address . ': ' . $message);
    }
}

<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

/**
 * Class ChallengeFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class ChallengeFailed extends Base
{
    /**
     * @param string $hydroAddressId
     * @param string $message
     * @return ChallengeFailed
     */
    public static function forHydroAddressId(string $hydroAddressId, string $message): ChallengeFailed
    {
        return new static('Could not whitelist address ' . $hydroAddressId . ': ' . $message);
    }
}

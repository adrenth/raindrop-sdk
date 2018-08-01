<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

use Exception;

/**
 * Class UnregisterUserFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class UnregisterUserFailed extends Base
{
    /**
     * @param string $hydroId
     * @param string $message
     * @param Exception|null $previousException
     * @return UnregisterUserFailed
     */
    public static function withHydroId(
        string $hydroId,
        string $message,
        Exception $previousException = null
    ): UnregisterUserFailed {
        return new static(sprintf(
            'Could not unregister user with Hydro ID %s: %s',
            $hydroId,
            $message
        ), 0, $previousException);
    }
}

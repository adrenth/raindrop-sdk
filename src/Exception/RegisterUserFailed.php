<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

use Exception;

/**
 * Class RegisterUserFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class RegisterUserFailed extends Base
{
    /**
     * @param string $hydroId
     * @param string $message
     * @param Exception|null $previousException
     * @return RegisterUserFailed
     */
    public static function withHydroId(
        string $hydroId,
        string $message,
        Exception $previousException = null
    ): RegisterUserFailed {
        return new static(sprintf(
            'Could not register user with Hydro ID %s: %s',
            $hydroId,
            $message
        ), 0, $previousException);
    }
}

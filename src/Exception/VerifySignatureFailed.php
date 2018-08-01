<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

use Exception;

/**
 * Class VerifySignatureFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
class VerifySignatureFailed extends \RuntimeException
{
    /**
     * @param string $hydroId
     * @param string $message
     * @param Exception|null $previousException
     * @return VerifySignatureFailed
     */
    public static function withHydroId(
        string $hydroId,
        string $message,
        Exception $previousException = null
    ): VerifySignatureFailed {
        return new static(sprintf(
            'Could not verify signature with Hydro ID %s: %s',
            $hydroId,
            $message
        ), 0, $previousException);
    }
}

<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiRequestFailed
 *
 * @package Adrenth\Raindrop\Exception
 */
final class ApiRequestFailed extends Base
{
    public const UNKNOWN_ERROR = 0;
    public const INTERNAL_SERVER_ERROR = 1;
    public const INVALID_JSON = 2;

    /**
     * @var array
     */
    private static $errorMessages = [
        self::UNKNOWN_ERROR => 'Unknown error occurred.',
        self::INTERNAL_SERVER_ERROR => 'Internal server error returned from the Hydro API.',
        self::INVALID_JSON => 'Invalid JSON response from the Hydro API',
    ];

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param int $code
     * @param ResponseInterface $response
     * @return ApiRequestFailed
     */
    public static function withCode(int $code, ResponseInterface $response): ApiRequestFailed
    {
        return self::withMessage(
            (string) (self::$errorMessages[$code] ?? self::$errorMessages[self::UNKNOWN_ERROR]),
            $response
        );
    }

    /**
     * @param string $message
     * @param ResponseInterface $response
     * @return ApiRequestFailed
     */
    public static function withMessage(string $message, ResponseInterface $response): ApiRequestFailed
    {
        $self = new self($message);
        $self->response = $response;

        return $self;
    }
}

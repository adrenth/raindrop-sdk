<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

/**
 * Class ApiAccessToken
 *
 * @package Adrenth\Raindrop
 */
class ApiAccessToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $expiresIn;

    /**
     * @param string $token
     * @param int $expiresIn
     */
    public function __construct(string $token, int $expiresIn)
    {
        $this->token = $token;
        $this->expiresIn = $expiresIn;
    }

    /**
     * @param string $token
     * @param int $expiresIn
     * @param int $expireOffset
     * @return ApiAccessToken
     */
    public static function create(string $token, int $expiresIn, int $expireOffset = 60): ApiAccessToken
    {
        return new self(
            $token,
            $expiresIn - $expireOffset
        );
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return time() > $this->expiresIn;
    }
}

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
    const EXPIRE_OFFSET = 60;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $expiresAt;

    /**
     * @param string $token
     * @param int $expiresAt
     */
    public function __construct(string $token, int $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    /**
     * @param string $token
     * @param int $expiresAt
     * @param int $expireOffset
     * @return ApiAccessToken
     */
    public static function create(
        string $token,
        int $expiresAt,
        int $expireOffset = self::EXPIRE_OFFSET
    ): ApiAccessToken {
        return new self(
            $token,
            $expiresAt - $expireOffset
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
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return time() > $this->expiresAt;
    }
}

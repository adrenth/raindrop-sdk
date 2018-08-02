<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;
use Adrenth\Raindrop\Exception\UnableToAcquireAccessToken;

/**
 * Class FileTokenStorage
 *
 * @package Adrenth\Raindrop\TokenStorage
 */
class FileTokenStorage implements TokenStorage
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): ApiAccessToken
    {
        if (!is_readable($this->filename)) {
            throw new UnableToAcquireAccessToken('Access Token is not found in the storage.');
        }

        $data = file_get_contents($this->filename);

        if (!empty($data) && substr_count($data, '|') === 1) {
            $data = explode('|', $data);
            return ApiAccessToken::create($data[0] ?? '', (int) ($data[1] ?? 0));
        }

        throw new UnableToAcquireAccessToken('Access Token is not found in the storage.');
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(ApiAccessToken $token)
    {
        file_put_contents($this->filename, $token->getToken() . '|'. $token->getExpiresAt());
    }

    /**
     * {@inheritdoc}
     */
    public function unsetAccessToken()
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
}

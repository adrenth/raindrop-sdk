<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;

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
    public function getAccessToken(): ?ApiAccessToken
    {
        if (!is_readable($this->filename)) {
            return null;
        }

        $data = file_get_contents($this->filename);

        if (!empty($data) && substr_count($data, '|') === 1) {
            $data = explode('|', $data);
            return ApiAccessToken::create($data[0] ?? '', (int) ($data[1] ?? 0));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(ApiAccessToken $token): void
    {
        file_put_contents($this->filename, $token->getToken() . '|'. $token->getExpiresIn());
    }

    /**
     * {@inheritdoc}
     */
    public function unsetAccessToken(): void
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
}

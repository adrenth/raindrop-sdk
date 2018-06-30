<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\TokenStorage;

use Adrenth\Raindrop\AccessToken;

/**
 * Class FileTokenStorage
 *
 * @package Src\Classes\SapApi\TokenStorage
 */
class FileTokenStorage implements TokenStorageInterface
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
    public function getAccessToken(): ?AccessToken
    {
        $data = file_get_contents($this->filename);

        if (!empty($data)) {
            $data = explode('|', $data);
            return new AccessToken($data[0] ?? '', (int) ($data[1] ?? 0));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(AccessToken $token): void
    {
        file_put_contents($this->filename, $token->getToken() . '|'. $token->getExpiresIn());
    }

    /**
     * {@inheritdoc}
     */
    public function unsetAccessToken(): void
    {
        unlink($this->filename);
    }
}

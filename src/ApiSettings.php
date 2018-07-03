<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Environment\Environment;

/**
 * Class ApiSettings
 *
 * @package Adrenth\Raindrop
 */
class ApiSettings
{
    /**
     * Client ID
     *
     * @var string
     */
    protected $clientId;

    /**
     * Client Secret
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * API Environment
     *
     * @var Environment
     */
    protected $environment;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param Environment $environment
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        Environment $environment
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }
}

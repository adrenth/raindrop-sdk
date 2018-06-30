<?php

declare(strict_types=1);

namespace Adrenth\Raindrop;

use Adrenth\Raindrop\Environment\EnvironmentInterface;

/**
 * Class Settings
 *
 * @package Adrenth\Raindrop
 */
class Settings
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
     * @var EnvironmentInterface
     */
    protected $environment;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param EnvironmentInterface $environment
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        EnvironmentInterface $environment
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
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environment;
    }
}

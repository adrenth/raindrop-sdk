<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Environment;

/**
 * Class SandboxEnvironment
 *
 * @package Adrenth\Raindrop\Environment
 */
class SandboxEnvironment implements Environment
{
    /**
     * {@inheritdoc}
     */
    public function getApiUrl(): string
    {
        return 'https://sandbox.hydrogenplatform.com';
    }
}

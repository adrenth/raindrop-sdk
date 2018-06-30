<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Environment;

/**
 * Class EnvironmentInterface
 *
 * @package Adrenth\Raindrop
 */
interface EnvironmentInterface
{
    /**
     * @return string
     */
    public function getApiUrl(): string;
}

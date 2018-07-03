<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Environment;

/**
 * Class Environment
 *
 * @package Adrenth\Raindrop
 */
interface Environment
{
    /**
     * @return string
     */
    public function getApiUrl(): string;
}

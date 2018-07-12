<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Environment;

/**
 * Class ProductionEnvironment
 *
 * @package Adrenth\Raindrop\Environment
 */
class ProductionEnvironment implements Environment
{
    /**
     * {@inheritdoc}
     */
    public function getApiUrl(): string
    {
        return 'https://api.hydrogenplatform.com';
    }
}

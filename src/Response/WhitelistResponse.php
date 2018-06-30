<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Response;

/**
 * Class HydroAddress
 *
 * @package Adrenth\Raindrop
 */
class WhitelistResponse
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $transactionHash;

    /**
     * @param string $identifier
     * @param string $transactionHash
     */
    public function __construct(string $identifier, string $transactionHash)
    {
        $this->identifier = $identifier;
        $this->transactionHash = $transactionHash;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }
}

<?php

declare(strict_types=1);

namespace Adrenth\Raindrop\Response;

/**
 * Class ChallengeResponse
 *
 * @package Adrenth\Raindrop\Response
 */
class ChallengeResponse
{
    /**
     * The challenge amount.
     *
     * @var string
     */
    private $amount;

    /**
     * The challenge string.
     *
     * @var integer
     */
    private $challenge;

    /**
     * The unique identifier assigned to your firm.
     *
     * @var integer
     */
    private $partnerId;

    /**
     * The hash of the transaction that updates the user’s raindrop requirements.
     *
     * @var string
     */
    private $transactionHash;

    /**
     * @param string $amount
     * @param int $challenge
     * @param int $partnerId
     * @param string $transactionHash
     */
    public function __construct(string $amount, int $challenge, int $partnerId, string $transactionHash)
    {
        $this->amount = $amount;
        $this->challenge = $challenge;
        $this->partnerId = $partnerId;
        $this->transactionHash = $transactionHash;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getChallenge(): int
    {
        return $this->challenge;
    }

    /**
     * @return int
     */
    public function getPartnerId(): int
    {
        return $this->partnerId;
    }

    /**
     * @return string
     */
    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }
}

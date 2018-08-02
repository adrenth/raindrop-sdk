<?php

declare(strict_types=1);

namespace AdrenthTests\Raindrop;

use Adrenth\Raindrop\ApiAccessToken;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiAccessTokenTest
 *
 * @package AdrenthTests\Raindrop
 */
final class ApiAccessTokenTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCorrectlyInstantiatedUsingTheConstructor()
    {
        $accessToken = new ApiAccessToken('token', 133337);

        self::assertEquals('token', $accessToken->getToken());
        self::assertEquals(133337, $accessToken->getExpiresAt());
        self::assertTrue($accessToken->isExpired());
    }

    /**
     * @test
     */
    public function itShouldBeCorrectlyInstantiatedUsingTheFactoryMethod()
    {
        $accessToken = ApiAccessToken::create('token', 133337);

        self::assertEquals('token', $accessToken->getToken());
        self::assertEquals(133337 - ApiAccessToken::EXPIRE_OFFSET, $accessToken->getExpiresAt());
        self::assertTrue($accessToken->isExpired());
    }

    /**
     * @test
     */
    public function itShouldntBeExpired()
    {
        $accessToken = new ApiAccessToken('token', time() + 3600);

        self::assertFalse($accessToken->isExpired());
    }

    /**
     * @test
     */
    public function itShouldBeExpired()
    {
        $accessToken = new ApiAccessToken('token', time() - 3600);

        self::assertTrue($accessToken->isExpired());
    }
}

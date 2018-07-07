<?php

declare(strict_types=1);

namespace AdrenthTests\Raindrop\TokenStorage;

use Adrenth\Raindrop\ApiAccessToken;
use Adrenth\Raindrop\TokenStorage\FileTokenStorage;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * Class FileTokenStorageTest
 *
 * @package AdrenthTests\Raindrop\TokenStorage
 */
final class FileTokenStorageTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @test
     */
    public function itShouldReturnNullWhenFileDoesNotExist(): void
    {
        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));

        self::assertNull($storage->getAccessToken());
    }

    /**
     * @test
     */
    public function itShouldReturnNullWhenFileIsEmpty(): void
    {
        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent(''));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));

        self::assertNull($storage->getAccessToken());
    }

    /**
     * @test
     */
    public function itShouldReturnNullWhenFileIsInvalid(): void
    {
        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent('invalid_contents'));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));

        self::assertNull($storage->getAccessToken());
    }

    /**
     * @test
     */
    public function itShouldReturnAnAccessToken(): void
    {
        $token = 'access_token';
        $expiresIn = 3600;

        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent("$token|$expiresIn"));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $accessToken = $storage->getAccessToken();

        self::assertNotNull($accessToken);
        self::assertEquals($token, $accessToken->getToken());
        self::assertEquals($expiresIn - ApiAccessToken::EXPIRE_OFFSET, $accessToken->getExpiresIn());
    }

    /**
     * @test
     */
    public function itShouldCreateAnFileWhenSettingTheAccessToken(): void
    {
        $token = 'access_token';
        $expiresIn = 3600;

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->setAccessToken(ApiAccessToken::create($token, $expiresIn));

        self::assertTrue($this->root->hasChild('root/token.txt'));

        /** @var vfsStreamFile $child */
        $child = $this->root->getChild('root/token.txt');
        self::assertEquals($token . '|' . ($expiresIn - ApiAccessToken::EXPIRE_OFFSET), $child->getContent());
    }

    /**
     * @test
     */
    public function itShouldDeleteTheFileWhenUnsettingTheAccessToken(): void
    {
        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->setAccessToken(ApiAccessToken::create('token', 3600));

        self::assertTrue($this->root->hasChild('root/token.txt'));

        $storage->unsetAccessToken();

        self::assertFalse($this->root->hasChild('root/token.txt'));
    }
}

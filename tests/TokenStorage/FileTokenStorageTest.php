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
    protected function setUp()
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @test
     * @expectedException \Adrenth\Raindrop\Exception\UnableToAcquireAccessToken
     */
    public function itShouldThrowAnExceptionWhenFileDoesNotExist()
    {
        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->getAccessToken();
    }

    /**
     * @test
     * @expectedException \Adrenth\Raindrop\Exception\UnableToAcquireAccessToken
     */
    public function itShouldThrowAnExceptionWhenFileIsEmpty()
    {
        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent(''));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->getAccessToken();
    }

    /**
     * @test
     * @expectedException \Adrenth\Raindrop\Exception\UnableToAcquireAccessToken
     */
    public function itShouldThrowAnExceptionWhenFileIsInvalid()
    {
        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent('invalid_contents'));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->getAccessToken();
    }

    /**
     * @test
     */
    public function itShouldReturnAnAccessToken()
    {
        $token = 'access_token';
        $expiresAt = time() + 3600;

        $this->root->addChild((new vfsStreamFile('token.txt'))->withContent("$token|$expiresAt"));

        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $accessToken = $storage->getAccessToken();

        self::assertNotNull($accessToken);
        self::assertEquals($token, $accessToken->getToken());
        self::assertEquals($expiresAt - ApiAccessToken::EXPIRE_OFFSET, $accessToken->getExpiresAt());
    }

    /**
     * @test
     */
    public function itShouldCreateAnFileWhenSettingTheAccessToken()
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
    public function itShouldDeleteTheFileWhenUnsettingTheAccessToken()
    {
        $storage = new FileTokenStorage(vfsStream::url('root/token.txt'));
        $storage->setAccessToken(ApiAccessToken::create('token', 3600));

        self::assertTrue($this->root->hasChild('root/token.txt'));

        $storage->unsetAccessToken();

        self::assertFalse($this->root->hasChild('root/token.txt'));
    }
}

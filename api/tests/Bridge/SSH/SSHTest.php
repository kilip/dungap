<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH;

use Dungap\Bridge\SSH\SecureException;
use Dungap\Bridge\SSH\Setting;
use Dungap\Bridge\SSH\SSH;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Net\SSH2;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SSHTest extends TestCase
{
    private MockObject|SSH2 $client;
    private MockObject|LoggerInterface $logger;
    private SSH $ssh;
    private Setting $setting;

    protected function setUp(): void
    {
        $this->setting = new Setting(
            username: 'johndoe',
            privateKey: __DIR__.'/fixtures/dungap'
        );
        $this->client = $this->createMock(SSH2::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->ssh = new SSH(
            host: '192.168.1.1',
            setting: $this->setting,
            logger: $this->logger,
            client: $this->client
        );
    }

    public function testRun(): void
    {
        $this->client->expects($this->once())
            ->method('login')
            ->with('johndoe', $this->isInstanceOf(AsymmetricKey::class))
            ->willReturn(true);

        $this->client->expects($this->once())
            ->method('exec')
            ->with('some command');

        $this->ssh->addCommand('some command');
        $this->ssh->run();
    }

    public function testFailedLogin(): void
    {
        $this->client->expects($this->once())
            ->method('login')
            ->willReturn(false);

        $this->expectException(SecureException::class);

        $this->ssh->run();
    }

    public function testOnRun(): void
    {
        $this->logger->expects($this->once())
            ->method('info');

        $this->ssh->onRun($expected = 'some output');

        $output = $this->ssh->getOutput();
        $this->assertSame($expected, $output);
    }
}

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

use Dungap\Bridge\SSH\Configuration;
use Dungap\Bridge\SSH\Service\SSH;
use Dungap\Bridge\SSH\SSHException;
use phpseclib3\Net\SSH2;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SSHTest extends TestCase
{
    private MockObject|SSH2 $client;
    private MockObject|LoggerInterface $logger;
    private Configuration $config;
    private SSH $ssh;

    protected function setUp(): void
    {
        $this->client = $this->createMock(SSH2::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->config = new Configuration(
            host: 'localhost',
            username: 'admin',
            password: 'admin'
        );
        $this->ssh = new SSH(
            config: $this->config,
            logger: $this->logger,
            client: $this->client
        );
    }

    public function testGetConfig(): void
    {
        $this->assertSame($this->config, $this->ssh->getConfig());
    }

    public function testExecute(): void
    {
        $this->client->expects($this->once())
            ->method('login')
            ->with('admin', 'admin')
            ->willReturn(true);
        $this->client->expects($this->once())
            ->method('exec')
            ->with('command')
            ->willReturn('some output');

        $this->assertSame(
            'some output',
            $this->ssh->execute('command')
        );
    }

    public function testLoginFailed(): void
    {
        $this->client->expects($this->once())
            ->method('login')
            ->willReturn(false);

        $this->expectException(SSHException::class);

        $this->ssh->execute('command');
    }
}

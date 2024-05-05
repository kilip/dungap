<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Service;

use Dungap\Bridge\SSH\Contracts\NodeConfigInterface;
use Dungap\Bridge\SSH\Contracts\NodeConfigRepositoryInterface;
use Dungap\Bridge\SSH\Service\SSHFactory;
use Dungap\Contracts\Node\NodeInterface;
use PHPUnit\Framework\TestCase;

class SSHFactoryTest extends TestCase
{
    public function testCreateSshClient(): void
    {
        $repository = $this->createMock(NodeConfigRepositoryInterface::class);
        $nodeConfig = $this->createMock(NodeConfigInterface::class);
        $node = $this->createMock(NodeInterface::class);

        $factory = new SSHFactory(
            $repository,
            'admin',
            'admin',
            __DIR__.'/fixtures/test',
            5,
            22
        );

        $node->method('getHostname')
            ->willReturn('localhost');

        $repository->expects($this->once())
            ->method('findByNode')
            ->willReturn($nodeConfig);

        $nodeConfig->expects($this->once())
            ->method('getUsername');
        $nodeConfig->expects($this->once())
            ->method('getPassword');
        $nodeConfig->expects($this->once())
            ->method('getPrivateKey');
        $nodeConfig->expects($this->once())
            ->method('getTimeout');
        $nodeConfig->expects($this->once())
            ->method('getPort');

        $factory->createSshClient($node);
    }
}

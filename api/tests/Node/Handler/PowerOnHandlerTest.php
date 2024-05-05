<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Handler;

use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\PowerOnProcessorInterface;
use Dungap\Dungap;
use Dungap\Node\Command\PowerOnCommand;
use Dungap\Node\Handler\PowerOnHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class PowerOnHandlerTest extends TestCase
{
    private MockObject|FeatureRepositoryInterface $features;
    private MockObject|FeatureInterface $feature;
    private MockObject|PowerOnProcessorInterface $processor;
    private MockObject|LoggerInterface $logger;
    private MockObject|NodeInterface $node;

    private PowerOnCommand $command;
    private PowerOnHandler $handler;

    public function setUp(): void
    {
        $this->features = $this->createMock(FeatureRepositoryInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $this->processor = $this->createMock(PowerOnProcessorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->handler = new PowerOnHandler(
            $this->features,
            [$this->processor, $this->processor],
            $this->logger
        );

        $this->command = new PowerOnCommand(Uuid::v7());

        $this->processor->method('getDriverName')
            ->willReturn(Dungap::SshDriver, Dungap::RouterOsDriver);

        $this->feature->method('getDriver')
            ->willReturn(Dungap::RouterOsDriver);
        $this->feature->method('getNode')
            ->willReturn($this->node);
    }

    public function testInvoke(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, Dungap::PowerOnFeature)
            ->willReturn($this->feature)
        ;

        $this->processor->expects($this->once())
            ->method('process')
            ->with($this->feature);

        $this->handler->__invoke($this->command);
    }

    public function testWithUnconfiguredFeature(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, Dungap::PowerOnFeature)
            ->willReturn(null)
        ;

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke($this->command);
    }

    public function testWithProcessorError(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, Dungap::PowerOnFeature)
            ->willReturn($this->feature)
        ;

        $this->processor->expects($this->once())
            ->method('process')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke($this->command);
    }

    public function testWithInvalidProcessor(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, Dungap::PowerOnFeature)
            ->willReturn($this->feature)
        ;

        $this->processor = $this->createMock(PowerOnProcessorInterface::class);
        $this->processor->method('getDriverName')
            ->willReturn('foo');

        $handler = new PowerOnHandler(
            $this->features,
            [$this->processor, $this->processor],
            $this->logger
        );

        $this->logger->expects($this->once())
            ->method('error');

        $handler($this->command);
    }
}

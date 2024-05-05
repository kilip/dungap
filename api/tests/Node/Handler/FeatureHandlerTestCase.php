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
use Dungap\Contracts\Node\FeatureProcessorInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\PowerOnProcessorInterface;
use Dungap\Dungap;
use Dungap\Node\Command\AbstractFeatureCommand;
use Dungap\Node\Handler\AbstractFeatureHandler;
use Dungap\Node\Handler\PowerOffHandler;
use Dungap\Node\Handler\PowerOnHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class FeatureHandlerTestCase extends TestCase
{
    protected MockObject|FeatureRepositoryInterface $features;
    protected MockObject|FeatureInterface $feature;
    protected MockObject|PowerOnProcessorInterface $processor;
    protected MockObject|LoggerInterface $logger;
    protected MockObject|NodeInterface $node;
    protected AbstractFeatureCommand $command;
    protected AbstractFeatureHandler|PowerOnHandler|PowerOffHandler $handler;
    protected string $handlerClass;
    protected string $commandClass;
    protected string $featureName;

    protected function setUp(): void
    {
        $this->features = $this->createMock(FeatureRepositoryInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $this->processor = $this->createMock(FeatureProcessorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->node = $this->createMock(NodeInterface::class);

        $this->handler = new $this->handlerClass(
            $this->features,
            [$this->processor, $this->processor],
            $this->logger
        );

        $this->command = new $this->commandClass(Uuid::v7());

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
            ->with($this->command->deviceId, $this->featureName)
            ->willReturn($this->feature)
        ;

        $this->processor->expects($this->once())
            ->method('process')
            ->with($this->feature);

        $handler = $this->handler;
        $handler($this->command);
    }

    public function testWithUnconfiguredFeature(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, $this->featureName)
            ->willReturn(null)
        ;

        $this->logger->expects($this->once())
            ->method('error');

        $handler = $this->handler;
        $handler($this->command);
    }

    public function testWithProcessorError(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, $this->featureName)
            ->willReturn($this->feature)
        ;

        $this->processor->expects($this->once())
            ->method('process')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');

        $handler = $this->handler;
        $handler($this->command);
    }

    public function testWithInvalidProcessor(): void
    {
        $this->features->expects($this->once())
            ->method('findByFeature')
            ->with($this->command->deviceId, $this->featureName)
            ->willReturn($this->feature)
        ;

        $this->processor = $this->createMock(PowerOnProcessorInterface::class);
        $this->processor->method('getDriverName')
            ->willReturn('foo');

        $handler = new $this->handlerClass(
            $this->features,
            [$this->processor, $this->processor],
            $this->logger
        );

        $this->logger->expects($this->once())
            ->method('error');

        $handler($this->command);
    }
}

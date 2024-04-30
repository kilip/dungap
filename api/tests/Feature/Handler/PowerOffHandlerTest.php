<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Feature\Handler;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Enum\EnumDeviceFeature;
use Dungap\Contracts\Feature\FeatureInterface;
use Dungap\Contracts\Feature\FeatureRepositoryInterface;
use Dungap\Contracts\Feature\PowerOffDriverInterface;
use Dungap\Device\Command\PowerOffCommand;
use Dungap\Feature\Handler\PowerOffHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PowerOffHandlerTest extends TestCase
{
    private MockObject|FeatureRepositoryInterface $features;
    private MockObject|FeatureInterface $feature;
    private MockObject|PowerOffDriverInterface $driver;
    private MockObject|LoggerInterface $logger;
    private PowerOffCommand $powerOffCommand;
    private PowerOffHandler $handler;

    protected function setUp(): void
    {
        $this->features = $this->createMock(FeatureRepositoryInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $device = $this->createMock(DeviceInterface::class);
        $this->driver = $this->createMock(PowerOffDriverInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new PowerOffHandler(
            $this->features,
            [$this->driver],
            $this->logger,
        );
        $this->powerOffCommand = new PowerOffCommand('some-id');

        $this->feature->method('getDevice')
            ->willReturn($device);
    }

    public function testInvoke(): void
    {
        $this->features->expects($this->once())
            ->method('findByDevice')
            ->with('some-id', EnumDeviceFeature::PowerOff->value)
            ->willReturn($this->feature);

        $this->feature->expects($this->once())
            ->method('getDriver')
            ->willReturn('router-os');

        $this->driver->expects($this->once())
            ->method('getName')
            ->willReturn('router-os');

        $this->driver->expects($this->once())
            ->method('process')
            ->with($this->feature)
            ->willReturn(true)
        ;

        $this->logger->expects($this->exactly(2))
            ->method('notice');

        $handler = $this->handler;
        $handler($this->powerOffCommand);
    }

    public function testWithUnmatchedDriver(): void
    {
        $this->features->expects($this->once())
            ->method('findByDevice')
            ->willReturn($this->feature);

        $this->feature->expects($this->once())
            ->method('getDriver')
            ->willReturn('foo');

        $this->driver->expects($this->once())
            ->method('getName')
            ->willReturn('bar');

        $this->driver->expects($this->never())
            ->method('process');

        $this->logger->expects($this->once())
            ->method('warning');

        $this->handler->__invoke($this->powerOffCommand);
    }

    public function testWithFailedProcess(): void
    {
        $this->features->expects($this->once())
            ->method('findByDevice')
            ->willReturn($this->feature);

        $this->feature->expects($this->once())
            ->method('getDriver')
            ->willReturn('foo');

        $this->driver->expects($this->once())
            ->method('getName')
            ->willReturn('foo');

        $this->driver->expects($this->once())
            ->method('process')
            ->willReturn(false)
        ;

        $this->logger->expects($this->once())
            ->method('warning');

        $this->handler->__invoke($this->powerOffCommand);
    }

    public function testWithError(): void
    {
        $this->features->expects($this->once())
            ->method('findByDevice')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');
        $this->handler->__invoke($this->powerOffCommand);
    }
}

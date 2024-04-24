<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Handler;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\PowerOffProcessorInterface;
use Dungap\Device\Command\PowerOffCommand;
use Dungap\Device\Handler\PowerOffHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class PowerOffHandlerTest extends TestCase
{
    private MockObject|PowerOffProcessorInterface $processor;
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|DeviceInterface $device;
    private PowerOffCommand $command;
    private PowerOffHandler $handler;

    public function setUp(): void
    {
        $this->processor = $this->createMock(PowerOffProcessorInterface::class);
        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->command = new PowerOffCommand($uuid = Uuid::v1());
        $this->handler = new PowerOffHandler(
            deviceRepository: $this->deviceRepository,
            // ensure break after power off have been successfully processed
            processors: [$this->processor, $this->processor]
        );

        $this->deviceRepository->expects($this->atLeastOnce())
            ->method('findById')
            ->with($this->command->deviceId)
            ->willReturn($this->device);
    }

    public function testInvoke(): void
    {
        $this->processor->expects($this->once())
            ->method('supports')
            ->willReturn(true);

        $this->processor->expects($this->once())
            ->method('process')
            ->with($this->device)
            ->willReturn(true);

        $this->handler->__invoke($this->command);
    }
}

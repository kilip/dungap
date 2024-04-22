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
use Dungap\Contracts\Device\DeviceScannerInterface;
use Dungap\Device\Command\NetworkScanCommand;
use Dungap\Device\DTO\ResultDevice;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NetworkScanHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|EventDispatcherInterface $dispatcher;
    private \Dungap\Device\Handler\NetworkScanHandler $handler;

    protected function setUp(): void
    {
        $scanner = $this->createMock(DeviceScannerInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);

        $result = new ResultDevice(
            ipAddress: 'ip',
            hostname: 'hostname',
            vendor: 'vendor',
            macAddress: 'mac'
        );

        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);

        $scanner->expects($this->once())
            ->method('scan')
            ->with($this->isInstanceOf(NetworkScanCommand::class))
            ->willReturn([$result]);

        $this->handler = new \Dungap\Device\Handler\NetworkScanHandler(
            scanner: $scanner,
            deviceRepository: $this->deviceRepository,
            dispatcher: $this->dispatcher
        );
    }

    public function testInvoke(): void
    {
        $handler = $this->handler;
        $dispatcher = $this->dispatcher;
        $device = $this->createMock(DeviceInterface::class);

        $this->deviceRepository->expects($this->once())
            ->method('create')
            ->willReturn($device);

        $device->expects($this->once())
            ->method('setDraft')
            ->with(true)
            ->willReturnSelf();

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($device);

        $this->deviceRepository->expects($this->once())
            ->method('store')
            ->with($device);

        $handler(new NetworkScanCommand(['target']));
    }

    /**
     * @dataProvider getTestInvokeWithExistingDevice
     */
    public function testInvokeWithExistingDevice(string $method, string $value): void
    {
        $handler = $this->handler;
        $device = $this->createMock(DeviceInterface::class);
        $this->deviceRepository->expects($this->once())
            ->method($method)
            ->with($this->equalTo($value))
            ->willReturn($device);

        $handler(new NetworkScanCommand(['target']));
    }

    /**
     * @return array<int,array<int,string>>
     */
    public function getTestInvokeWithExistingDevice(): array
    {
        return [
            ['findByMacAddress', 'mac'],
            ['findByIpAddress', 'ip'],
            ['findByHostname', 'hostname'],
        ];
    }
}

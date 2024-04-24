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

use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\OnlineCheckerInterface;
use Dungap\Contracts\Device\UptimeProcessorInterface;
use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Device\DTO\ResultDevice;
use Dungap\Device\Entity\Device;
use Dungap\Device\Handler\CheckOnlineHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class CheckOnlineHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|OnlineCheckerInterface $onlineChecker;
    private MockObject|Device $device;
    private MockObject|LoggerInterface $logger;
    private MockObject|UptimeProcessorInterface $uptimeProcessor;

    private CheckOnlineHandler $handler;
    private ResultDevice $resultDevice;
    private string $lockfile;

    protected function setUp(): void
    {
        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);
        $this->onlineChecker = $this->createMock(OnlineCheckerInterface::class);
        $this->device = $this->createMock(Device::class);
        $this->lockfile = sys_get_temp_dir().'/dungap/online.lck';
        $this->resultDevice = new ResultDevice(
            ipAddress: 'ip',
            hostname: 'hostname',
            vendor: 'vendor',
            macAddress: 'mac',
            online: true,
        );
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->uptimeProcessor = $this->createMock(UptimeProcessorInterface::class);
        $this->handler = new CheckOnlineHandler(
            $this->deviceRepository,
            $this->onlineChecker,
            [$this->uptimeProcessor],
            $this->logger
        );
        $this->device->method('getId')
            ->willReturn(Uuid::v1());

        $this->device->method('hasFeature')
            ->willReturn(true);

        $this->deviceRepository->method('findByIpAddress')
            ->with('ip')
            ->willReturn($this->device);

        ensureFileDirExists($this->lockfile);
    }

    public function testInvoke(): void
    {
        $this->onlineChecker->expects(self::once())
            ->method('run')
            ->willReturn([$this->resultDevice])
        ;

        $this->device->expects($this->exactly(3))
            ->method('isOnline')
            ->willReturn(false, true, true);

        $this->device->expects($this->once())
            ->method('setOnline')
            ->with(true);

        $uptime = new \DateTimeImmutable();
        $this->device->expects($this->once())
            ->method('setUptime')
            ->with($uptime);

        $this->uptimeProcessor->expects($this->once())
            ->method('process')
            ->with($this->device)
            ->willReturn($uptime);

        $this->deviceRepository->expects($this->once())
            ->method('store')
            ->with($this->device);

        $this->handler->__invoke(new CheckOnlineCommand($this->lockfile));
    }

    public function testWithOfflineDevice(): void
    {
        $resultDevice = new ResultDevice(
            ipAddress: 'ip',
            macAddress: 'mac',
            online: false,
        );

        $this->onlineChecker->expects(self::once())
            ->method('run')
            ->willReturn([$resultDevice]);

        $this->device->expects($this->exactly(3))
            ->method('isOnline')
            ->willReturn(true, false, false);
        $this->device->expects($this->once())
            ->method('setUptime')
            ->with(null);

        $this->handler->__invoke(new CheckOnlineCommand($this->lockfile));
    }

    public function testErrorHandling(): void
    {
        $this->onlineChecker->expects(self::once())
            ->method('run')
            ->willThrowException(new \Exception('test exception'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke(new CheckOnlineCommand($this->lockfile));
    }
}

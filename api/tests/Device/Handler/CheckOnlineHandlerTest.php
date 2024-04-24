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
use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Device\DTO\ResultDevice;
use Dungap\Device\Entity\Device;
use Dungap\Device\Handler\CheckOnlineHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CheckOnlineHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|OnlineCheckerInterface $onlineChecker;
    private MockObject|Device $device;
    private MockObject|LoggerInterface $logger;
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
        );
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new CheckOnlineHandler(
            $this->deviceRepository,
            $this->onlineChecker,
            $this->logger
        );

        ensureFileDirExists($this->lockfile);
    }

    public function testInvoke(): void
    {
        $this->onlineChecker->expects(self::once())
            ->method('run')
            ->willReturn([$this->resultDevice])
        ;

        $this->deviceRepository->expects($this->once())
            ->method('findByIpAddress')
            ->with('ip')
            ->willReturn($this->device);

        $this->device->expects($this->once())
            ->method('setOnline')
            ->with(true);

        $this->deviceRepository->expects($this->once())
            ->method('store')
            ->with($this->device);

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

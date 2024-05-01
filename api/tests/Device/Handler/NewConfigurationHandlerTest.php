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

use Dungap\Contracts\Device\CategoryRepositoryInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Device\Handler\NewConfigurationHandler;
use Dungap\Setting\Command\NewConfigurationCommand;
use Dungap\Setting\Config\Config;
use Dungap\Setting\Config\Device as DeviceConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class NewConfigurationHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $devices;
    private MockObject|DeviceInterface $device;
    private MockObject|LoggerInterface $logger;
    private MockObject|ServiceScannerInterface $serviceScanner;
    private NewConfigurationCommand $command;
    private NewConfigurationHandler $handler;

    protected function setUp(): void
    {
        $this->devices = $this->createMock(DeviceRepositoryInterface::class);
        $categories = $this->createMock(CategoryRepositoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->serviceScanner = $this->createMock(ServiceScannerInterface::class);
        $this->handler = new NewConfigurationHandler(
            $this->devices,
            $categories,
            $this->serviceScanner,
            $this->logger
        );

        $config = new Config();
        $config->setDevices([new DeviceConfig('zeus')]);
        $this->command = new NewConfigurationCommand($config);

        $this->devices->method('create')
            ->willReturn($this->device);
    }

    public function testInvoke(): void
    {
        $this->device->expects($this->once())
            ->method('setName')
            ->with('zeus');

        $this->devices->expects($this->once())
            ->method('store')
            ->with($this->device);

        $this->serviceScanner->expects($this->once())
            ->method('scan')
            ->with([$this->device]);

        $this->handler->__invoke($this->command);
    }

    public function testWhenErrorProcessingDevice(): void
    {
        $this->devices->expects($this->once())
            ->method('store')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke($this->command);
    }

    public function testWhenServiceScanningError(): void
    {
        $this->serviceScanner->expects($this->once())
            ->method('scan')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke($this->command);
    }
}

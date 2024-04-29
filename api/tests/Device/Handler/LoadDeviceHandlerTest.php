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

use Dungap\Contracts\Device\CategoryInterface;
use Dungap\Contracts\Device\CategoryRepositoryInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Device\Handler\LoadDeviceHandler;
use Dungap\Setting\Command\NewConfigurationCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoadDeviceHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|CategoryRepositoryInterface $categoryRepository;
    private MockObject|DeviceInterface $device;
    private MockObject|CategoryInterface $category;

    private LoadDeviceHandler $handler;

    public function setUp(): void
    {
        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->category = $this->createMock(CategoryInterface::class);
        $this->handler = new LoadDeviceHandler($this->deviceRepository, $this->categoryRepository);
    }

    public function testInvoke(): void
    {
        $handler = $this->handler;

        $command = new NewConfigurationCommand([
            'devices' => [
                [
                    'name' => 'zeus',
                    'ip' => '192.168.1.1',
                    'mac' => 'mac',
                    'hostname' => 'host',
                    'category' => null,
                ],
            ],
        ]);

        $this->deviceRepository->expects($this->once())
            ->method('findByIpOrName')
            ->with('192.168.1.1', 'zeus')
            ->willReturn(null);
        $this->deviceRepository->expects($this->once())
            ->method('create')
            ->willReturn($this->device);

        $this->categoryRepository->expects($this->once())
            ->method('findOrCreate')
            ->with('uncategorized')
            ->willReturn($this->category);
        $this->device->expects($this->once())
            ->method('setName');
        $this->device->expects($this->once())
            ->method('setIpAddress');
        $this->device->expects($this->once())
            ->method('setMacAddress');
        $this->device->expects($this->once())
            ->method('setHostname');
        $this->device->expects($this->once())
            ->method('setCategory')
            ->with($this->category);

        $this->deviceRepository->expects($this->once())
            ->method('store');

        $handler($command);
    }
}

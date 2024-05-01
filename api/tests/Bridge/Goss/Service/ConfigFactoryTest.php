<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Config\ConfigFactory;
use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigFileInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    private MockObject|GossConfigInterface $gossConfig;
    private MockObject|ServiceInterface $service;
    private MockObject|DeviceInterface $device;

    protected function setUp(): void
    {
        $this->gossConfig = $this->getMockBuilder(GossConfigInterface::class)->getMock();
        $this->service = $this->getMockBuilder(ServiceInterface::class)->getMock();
        $this->device = $this->getMockBuilder(DeviceInterface::class)->getMock();

        $this->service->method('getDevice')
            ->willReturn($this->device);
        $this->service->method('getPort')
            ->willReturn(22);
        $this->device->method('getIpAddress')
            ->willReturn('192.168.1.1');

        $this->gossConfig->method('getService')
            ->willReturn($this->service);
        $this->gossConfig->method('getType')
            ->willReturn(Constant::ValidatorTypeAddress);
        $this->gossConfig->method('getId')
            ->willReturn(null);
    }

    public function testCreate(): void
    {
        $factory = new ConfigFactory(
            sys_get_temp_dir().'/dungap/goss/config'
        );

        $configFile = $factory->create([$this->gossConfig]);

        $this->assertInstanceOf(GossConfigFileInterface::class, $configFile);
        $this->assertFileExists($configFile->getFileName());
    }
}

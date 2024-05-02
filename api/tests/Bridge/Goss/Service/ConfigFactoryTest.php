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

use Dungap\Bridge\Goss\Config\FileFactory;
use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileInterface;
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
    private MockObject|GossConfigRepositoryInterface $configRepository;

    protected function setUp(): void
    {
        $this->gossConfig = $this->createMock(GossConfigInterface::class);
        $this->service = $this->createMock(ServiceInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->configRepository = $this->createMock(GossConfigRepositoryInterface::class);

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
        $factory = new FileFactory(
            $this->configRepository,
            sys_get_temp_dir().'/dungap/goss/config'
        );

        $configFile = $factory->create(
            [$this->gossConfig],
            uniqid('goss_').'.yaml'
        );

        $this->assertInstanceOf(GossFileInterface::class, $configFile);
        $this->assertFileExists($configFile->getFileName());
    }
}

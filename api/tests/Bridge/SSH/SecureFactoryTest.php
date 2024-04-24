<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH;

use Dungap\Bridge\SSH\SecureFactory;
use Dungap\Bridge\SSH\Setting;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Setting\SettingFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SecureFactoryTest extends TestCase
{
    private MockObject|SettingFactoryInterface $settingFactory;
    private MockObject|DeviceInterface $device;
    private SecureFactory $factory;
    private Setting $global;

    protected function setUp(): void
    {
        $this->settingFactory = $this->createMock(SettingFactoryInterface::class);
        $this->factory = new SecureFactory(
            settingFactory: $this->settingFactory,
            username: 'john',
            privateKey: __DIR__.'/fixtures/dungap',
        );
        $this->device = $this->createMock(DeviceInterface::class);
        $this->global = new Setting(
            'johndoe',
            'changeme',
            __DIR__.'/fixtures/dungap'
        );

        $this->device->method('getId')
            ->willReturn(Uuid::v1());
        $this->device->method('getIpAddress')
            ->willReturn('192.168.1.1');

        $this->settingFactory->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['ssh.config.'.$this->device->getId(), Setting::class, false, null],
                ['ssh.config.global', Setting::class, true, $this->global],
            ]);
    }

    public function testCreateSshClient(): void
    {
        $this->factory->createSshClient($this->device);
    }

    public function testCreateSftpClient(): void
    {
        $this->factory->createSftpClient($this->device);
    }
}

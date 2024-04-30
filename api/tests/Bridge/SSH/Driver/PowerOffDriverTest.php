<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Driver;

use Dungap\Bridge\SSH\Driver\PowerOffDriver;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Device\SshInterface;
use Dungap\Contracts\Feature\FeatureInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PowerOffDriverTest extends TestCase
{
    private MockObject|SecureFactoryInterface $factory;
    private MockObject|SshInterface $ssh;
    private MockObject|DeviceInterface $device;
    private MockObject|FeatureInterface $feature;
    private MockObject|LoggerInterface $logger;
    private PowerOffDriver $driver;

    public function setUp(): void
    {
        $this->factory = $this->createMock(SecureFactoryInterface::class);
        $this->ssh = $this->createMock(SshInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->driver = new PowerOffDriver(
            $this->factory,
            $this->logger
        );
    }

    public function testProcess(): void
    {
        $this->feature->expects($this->once())
            ->method('getDevice')
            ->willReturn($this->device);
        $this->factory->expects($this->once())
            ->method('createSshClient')
            ->with($this->device)
            ->willReturn($this->ssh);

        $this->ssh->expects($this->once())
            ->method('addCommand')
            ->with('sudo poweroff');
        $this->ssh->expects($this->once())
            ->method('run');

        $this->assertTrue($this->driver->process($this->feature));
    }

    public function testWithError(): void
    {
        $this->feature->expects($this->once())
            ->method('getDevice')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->assertFalse($this->driver->process($this->feature));
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Processor;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Device\SshInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PowerOffProcessorTest extends TestCase
{
    private MockObject|SecureFactoryInterface $secureFactory;
    private MockObject|DeviceInterface $device;
    private MockObject|SshInterface $ssh;
    private PowerOffProcessor $processor;

    public function setUp(): void
    {
        $this->secureFactory = $this->createMock(SecureFactoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->ssh = $this->createMock(SshInterface::class);
        $this->processor = new PowerOffProcessor(
            secureFactory: $this->secureFactory
        );
    }

    public function testSupports(): void
    {
        $this->device->expects($this->exactly(2))
            ->method('hasFeature')
            ->willReturn(true);

        $this->assertTrue($this->processor->supports($this->device));
    }

    public function testProcess(): void
    {
        $this->secureFactory->expects($this->once())
            ->method('createSshClient')
            ->with($this->device)
            ->willReturn($this->ssh);

        $this->ssh->expects($this->once())
            ->method('addCommand')
            ->with('sudo poweroff');

        $this->assertTrue($this->processor->process($this->device));
    }

    public function testProcessWithException(): void
    {
        $this->secureFactory->expects($this->once())
            ->method('createSshClient')
            ->with($this->device)
            ->willReturn($this->ssh);

        $this->ssh->expects($this->once())
            ->method('addCommand')
            ->with('sudo poweroff')
            ->willThrowException(new \Exception('some ssh failed exception'))
        ;

        $this->assertFalse($this->processor->process($this->device));
    }
}

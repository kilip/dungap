<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Processor;

use Dungap\Bridge\SSH\Processor\UptimeCheckProcessor;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\SecureFactoryInterface;
use Dungap\Contracts\Device\SshInterface;
use PHPUnit\Framework\TestCase;

class UptimeCheckProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $sshFactory = $this->createMock(SecureFactoryInterface::class);
        $ssh = $this->createMock(SshInterface::class);
        $device = $this->createMock(DeviceInterface::class);

        $processor = new UptimeCheckProcessor($sshFactory);

        $sshFactory->expects($this->once())
            ->method('createSshClient')
            ->with($device)
            ->willReturn($ssh);

        $ssh->expects($this->once())
            ->method('addCommand')
            ->with('uptime -s');

        $ssh->expects($this->once())
            ->method('run');

        $ssh->expects($this->once())
            ->method('getOutput')
            ->willReturn('2024-04-20 10:34:01')
        ;

        $processor->process($device);
    }
}

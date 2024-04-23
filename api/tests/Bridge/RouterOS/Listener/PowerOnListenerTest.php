<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Listener;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Bridge\RouterOS\Listener\PowerOnListener;
use Dungap\Contracts\Device\DeviceInterface;
use PHPUnit\Framework\TestCase;

class PowerOnListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $device = $this->createMock(DeviceInterface::class);
        $listener = new PowerOnListener($request, 'bridge-1');

        $request->expects($this->once())
            ->method('request')
            ->with('POST', '/tool/wol');

        $device->expects($this->once())
            ->method('getMacAddress')
            ->will($this->returnValue('mac'));

        // null mac address testing
        $listener->__invoke($device);
    }
}

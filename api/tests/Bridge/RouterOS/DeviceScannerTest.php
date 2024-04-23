<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Bridge\RouterOS\DeviceScanner;
use Dungap\Device\Command\ScanDeviceCommand;
use Dungap\Device\DTO\ResultDevice;
use PHPUnit\Framework\TestCase;

class DeviceScannerTest extends TestCase
{
    public function testScan(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $json = json_decode(file_get_contents(__DIR__.'/fixtures/ip.dhcp-server.lease.json'), true);
        $json = [$json];
        $scanner = new DeviceScanner($request);

        $request->expects($this->once())
            ->method('request')
            ->with('GET', '/ip/dhcp-server/lease')
            ->willReturn($json);

        $results = $scanner->scan(new ScanDeviceCommand(['10.0.0.0/24']));

        $this->assertCount(1, $results);
        $this->assertInstanceOf(ResultDevice::class, $results[0]);
        $this->assertSame('192.168.1.1', $results[0]->ipAddress);
        $this->assertSame('AA:BB:CC:DD:EE:FF', $results[0]->macAddress);
        $this->assertSame('mars', $results[0]->hostname);
    }
}

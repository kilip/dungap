<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Bridge\RouterOS\Response\DhcpLeaseResponse;
use Dungap\Contracts\Device\DeviceScannerInterface;
use Dungap\Device\Command\ScanDeviceCommand;
use Dungap\Device\DTO\ResultDevice;

final readonly class DeviceScanner implements DeviceScannerInterface
{
    public function __construct(
        private RequestInterface $request
    ) {
    }

    public function scan(ScanDeviceCommand $command): array
    {
        $request = $this->request;
        $json = $request->request('GET', '/ip/dhcp-server/lease');

        $results = [];
        foreach ($json as $item) {
            $response = new DhcpLeaseResponse($item);
            $device = new ResultDevice(
                ipAddress: $response->address,
                hostname: $response->hostName,
                macAddress: $response->macAddress
            );
            $results[] = $device;
        }

        return $results;
    }
}

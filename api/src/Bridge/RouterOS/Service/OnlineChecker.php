<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Service;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Bridge\RouterOS\Response\DhcpLeaseResponse;
use Dungap\Contracts\Device\OnlineCheckerInterface;
use Dungap\Device\DTO\ResultDevice;

class OnlineChecker implements OnlineCheckerInterface
{
    public function __construct(
        private RequestInterface $request
    ) {
    }

    public function run(): array
    {
        $json = $this->request->request('GET', '/ip/dhcp-server/lease');
        $results = [];

        foreach ($json as $item) {
            $response = new DhcpLeaseResponse($item);
            if (!$response->disabled) {
                $results[] = new ResultDevice(
                    ipAddress: $response->address,
                    hostname: $response->hostName,
                    macAddress: $response->macAddress,
                    online: 'bound' == $response->status,
                );
            }
        }

        return $results;
    }
}

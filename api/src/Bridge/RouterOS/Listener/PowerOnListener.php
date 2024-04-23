<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Listener;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Device\DeviceConstant;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DeviceConstant::EventDevicePowerOn)]
class PowerOnListener
{
    public function __construct(
        private RequestInterface $request,

        #[Autowire('%env(ROUTEROS_WOL_INTERFACE)%')]
        private string $wolInterface,

        private ?LoggerInterface $logger = null
    ) {
    }

    public function __invoke(DeviceInterface $device): void
    {
        $mac = $device->getMacAddress();
        if (is_null($mac)) {
            return;
        }

        $this->logger?->info('[RouterOS] /tool/wol with mac {0}', [$mac, $device->getNickname()]);

        $payload = [
            'mac' => $mac,
            'interface' => $this->wolInterface,
        ];

        $this->request->request('POST', '/tool/wol', $payload);
    }
}

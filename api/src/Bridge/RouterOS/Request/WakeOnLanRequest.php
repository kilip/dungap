<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Request;

use Dungap\Bridge\RouterOS\Contracts\HttpClientFactoryInterface;
use Dungap\Bridge\RouterOS\Contracts\WakeOnLanRequestInterface;
use Dungap\Bridge\RouterOS\Exception;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autoconfigure(public: true)]
final readonly class WakeOnLanRequest implements WakeOnLanRequestInterface
{
    public function __construct(
        private HttpClientFactoryInterface $factory,
        #[Autowire('%env(DUNGAP_ROUTEROS_WOL_INTERFACE)%')]
        private string $interface
    ) {
    }

    public function execute(string $macAddress): void
    {
        try {
            $client = $this->factory->create();
            $client->request('POST', '/rest/tool/wol', [
                'json' => [
                    'mac' => $macAddress,
                    'interface' => $this->interface,
                ],
            ]);
        } catch (\Exception $e) {
            throw Exception::failToWakeOnLan($macAddress, $e->getMessage());
        }
    }
}

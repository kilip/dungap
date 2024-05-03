<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Config;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Config
{
    /**
     * @var iterable<Host>
     */
    private iterable $hosts = [];

    /**
     * @param array<string,mixed> $hosts
     */
    public function __construct(
        #[Autowire('%dungap.node.hosts%')]
        array $hosts,
    ) {
        foreach ($hosts as $host) {
            $this->addHost($host['name'], $host['ip'], $host['mac'], $host['note']);
        }
    }

    public function addHost(
        string $name,
        ?string $ip,
        ?string $mac,
        ?string $note
    ): void {
        $this->hosts[] = new Host($name, $ip, $mac, $note);
    }

    /**
     * @return iterable<Host>
     */
    public function getHosts(): iterable
    {
        return $this->hosts;
    }
}

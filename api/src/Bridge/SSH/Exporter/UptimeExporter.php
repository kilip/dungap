<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Exporter;

use Carbon\Carbon;
use Dungap\Bridge\SSH\Contracts\NodeExporterInterface;
use Dungap\Bridge\SSH\Contracts\SshInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Dungap;
use Dungap\State\Event\StateUpdatedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class UptimeExporter implements NodeExporterInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function process(NodeInterface $node, SshInterface $ssh): void
    {
        $uptime = $ssh->execute('uptime -s');
        $date = Carbon::parse($uptime);

        $event = new StateUpdatedEvent(
            entity: $node,
            name: $node->getStates()->uptime,
            state: $date->timestamp
        );

        $this->dispatcher->dispatch($event, Dungap::OnStateChanged);
    }
}

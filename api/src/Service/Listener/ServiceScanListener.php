<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Listener;

use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Dungap;
use Dungap\Node\Event\NodeAddedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Dungap::OnNodeAdded, method: 'onNodeAdded')]
final readonly class ServiceScanListener
{
    public function __construct(
        private ServiceScannerInterface $scanner
    ) {
    }

    public function onNodeAdded(NodeAddedEvent $event): void
    {
        $this->scanner->scan($event->node);
    }
}

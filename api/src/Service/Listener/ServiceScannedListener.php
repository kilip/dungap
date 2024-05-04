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

use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Dungap;
use Dungap\Service\Event\ServiceScannedEvent;
use Dungap\State\Event\StateUpdatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(event: Dungap::OnServiceScanned)]
final readonly class ServiceScannedListener
{
    public function __construct(
        private ServiceRepositoryInterface $services,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    public function __invoke(ServiceScannedEvent $event): void
    {
        if ($event->report->isSuccessful()) {
            $this->process($event);
        }
    }

    private function process(ServiceScannedEvent $event): void
    {
        $services = $this->services;
        $dispatcher = $this->dispatcher;
        $report = $event->report;

        $node = $report->getNode();
        $port = $report->getPort();
        $state = $report->isSuccessful() ? Dungap::OnlineState : Dungap::OfflineState;

        $service = $this->services->findByNodePort(
            $node,
            $port
        );

        if (is_null($service)) {
            $service = $this->services->create();
            $service->setPort($port);
            $service->setNode($node);
            $services->save($service);
        }

        $name = "node.service.".strval($port);
        $event = new StateUpdatedEvent(
            entityId: $service->getId(),
            name: $name,
            state: $state,
            relId: $node->getId()
        );

        $dispatcher->dispatch($event, Dungap::OnStateUpdated);
    }
}

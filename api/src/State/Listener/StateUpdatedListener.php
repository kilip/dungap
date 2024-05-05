<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State\Listener;

use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\Dungap;
use Dungap\State\Event\StateChangedEvent;
use Dungap\State\Event\StateUpdatedEvent;
use Dungap\State\StateException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(event: Dungap::OnStateUpdated)]
final readonly class StateUpdatedListener
{
    public function __construct(
        private StateRepositoryInterface $states,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(StateUpdatedEvent $event): void
    {
        $lastState = $this->states->getLastState($event->entityId, $event->name);
        $haveToChange = false;

        if (!$lastState instanceof StateInterface) {
            $haveToChange = true;
        } elseif ($lastState->getState() !== $event->state) {
            $haveToChange = true;
        }

        if ($haveToChange) {
            $this->updateState($event);
        }
    }

    private function updateState(StateUpdatedEvent $event): void
    {
        try {
            $state = $this->states->create();
            $state->setEntityId($event->entityId);
            $state->setName($event->name);
            $state->setRelId($event->relId);
            $state->setState($event->state);
            $state->setAttributes($event->attributes);
            $this->states->save($state);

            $changedEvent = new StateChangedEvent($event);
            $this->dispatcher->dispatch($changedEvent, Dungap::OnStateChanged);
        } catch (\Exception $e) {
            throw StateException::updateStateFailed($event->name, $event->state, $e->getMessage());
        }
    }
}

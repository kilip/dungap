<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State\Event;

class StateChangedEvent extends StateUpdatedEvent
{
    public function __construct(StateUpdatedEvent $event)
    {
        parent::__construct(
            $event->entity,
            $event->name,
            $event->state,
            $event->attributes,
            $event->related
        );
    }
}

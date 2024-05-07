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

use Dungap\Contracts\Core\IdentifiableInterface;
use Dungap\Contracts\State\StateInterface;

class StateChangedEvent
{
    public function __construct(
        public StateInterface $state,
        public IdentifiableInterface $entity,
        public ?IdentifiableInterface $related
    ) {
    }
}

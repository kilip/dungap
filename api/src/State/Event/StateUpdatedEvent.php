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

use Symfony\Component\Uid\Uuid;

class StateUpdatedEvent
{
    /**
     * @param array<string,mixed> $attributes
     */
    public function __construct(
        public Uuid $entityId,
        public string $name,
        public string $state,
        public array $attributes = [],
        public ?Uuid $relId = null
    ) {
    }
}

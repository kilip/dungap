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
use Symfony\Component\Uid\Uuid;

class StateUpdatedEvent
{
    public readonly Uuid $entityId;
    public ?Uuid $relId = null;

    /**
     * @param array<string,mixed> $attributes
     */
    public function __construct(
        public readonly IdentifiableInterface $entity,
        public readonly string $name,
        public readonly string $state,
        public readonly array $attributes = [],
        public readonly ?IdentifiableInterface $related = null,
    ) {
        $this->entityId = $entity->getId();
        if (!is_null($this->related)) {
            $this->relId = $related->getId();
        }
    }
}

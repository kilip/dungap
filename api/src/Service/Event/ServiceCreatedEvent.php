<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Event;

use Dungap\Contracts\Service\ServiceInterface;

class ServiceCreatedEvent
{
    public function __construct(
        public ServiceInterface $service
    ) {
    }
}

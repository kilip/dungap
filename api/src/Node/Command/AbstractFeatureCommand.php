<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Command;

use Symfony\Component\Uid\Uuid;

abstract readonly class AbstractFeatureCommand
{
    public function __construct(
        public Uuid $deviceId,
    ) {
    }
}

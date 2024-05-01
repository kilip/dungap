<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Config;

class Device
{
    public function __construct(
        public string $name,
        public ?string $hostname = null,
        public ?string $ip = null,
        public ?string $mac = null,
        public ?string $category = null,
    ) {
    }
}

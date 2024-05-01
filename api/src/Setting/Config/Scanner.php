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

class Scanner
{
    public function __construct(
        public int $port,
        public int $timeout = 500
    ) {
    }
}

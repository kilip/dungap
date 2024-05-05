<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH;

use phpseclib3\Crypt\Common\AsymmetricKey;

final readonly class Configuration
{
    public function __construct(
        public string $host,
        public string $username,
        public int $port = 22,
        public int $timeout = 10,
        public ?AsymmetricKey $key = null,
        public ?string $password = null,
    ) {
    }
}

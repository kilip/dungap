<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\DTO;

final readonly class ResultDevice
{
    public function __construct(
        public string $ipAddress,
        public ?string $hostname = null,
        public ?string $vendor = null,
        public ?string $macAddress = null,
    ) {
    }
}

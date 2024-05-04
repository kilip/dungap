<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Config;

use Dungap\Contracts\Service\ScannerConfigInterface;

final readonly class ScannerConfig implements ScannerConfigInterface
{
    public function __construct(
        private int $port,
        private int $timeout = 500
    ) {
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}

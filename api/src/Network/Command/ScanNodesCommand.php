<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Network\Command;

final readonly class ScanNodesCommand
{
    /**
     * @param array<int,string> $target
     */
    public function __construct(
        private array $target
    ) {
    }

    /**
     * @return array<int,string>
     */
    public function getTarget(): array
    {
        return $this->target;
    }
}

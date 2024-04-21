<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Network;

use Dungap\Network\ResultNode;

interface NetworkScannerInterface
{
    /**
     * @param array<int,string> $target
     *
     * @return array<int,ResultNode>
     */
    public function scan(array $target): array;
}

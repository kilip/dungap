<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Service;

use Dungap\Contracts\Node\NodeInterface;

interface ServiceScannerInterface
{
    public function scan(NodeInterface $node): void;

    public function addConfig(int $port, int $timeout): void;
}

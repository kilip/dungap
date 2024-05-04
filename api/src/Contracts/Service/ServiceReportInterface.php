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

interface ServiceReportInterface
{
    public function getNode(): NodeInterface;

    public function getPort(): int;

    public function getLatency(): ?float;

    public function isSuccessful(): bool;

    public function getTimeout(): int;
}

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

use Dungap\Contracts\Core\IdentifiableInterface;
use Dungap\Contracts\Node\NodeInterface;

interface ServiceInterface extends IdentifiableInterface
{
    public function setNode(NodeInterface $node): void;

    public function getNode(): NodeInterface;

    public function setPort(int $port): void;

    public function getPort(): int;
}

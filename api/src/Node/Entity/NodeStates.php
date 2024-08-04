<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Entity;

use Dungap\Contracts\Node\NodeInterface;

/**
 * An array list of node constants.
 */
final readonly class NodeStates
{
    public function __construct(
        public string $online,
        public string $uptime
    ) {
    }

    public static function create(NodeInterface $node): self
    {
        $name = $node->getName();

        return new self(
            online: "node.online.{$name}",
            uptime: "node.uptime.{$name}",
        );
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Node;

use Dungap\Contracts\Core\IdentifiableInterface;

interface FeatureInterface extends IdentifiableInterface
{
    public function setName(string $name): void;

    public function getName(): string;

    public function setDriver(string $driver): void;

    public function getDriver(): string;

    public function setNode(NodeInterface $node): void;

    public function getNode(): NodeInterface;
}

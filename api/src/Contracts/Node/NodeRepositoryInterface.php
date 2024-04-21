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

interface NodeRepositoryInterface
{
    public function findByMacAddress(string $macAddress): ?NodeInterface;

    public function findByIpAddress(string $ipAddress): ?NodeInterface;

    public function findByHostname(?string $hostname): ?NodeInterface;

    public function create(): NodeInterface;

    public function store(NodeInterface $node): void;
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Contracts;

use Dungap\Contracts\Node\NodeInterface;

interface NodeConfigInterface
{
    public function getNode(): NodeInterface;

    public function setNode(NodeInterface $node): void;

    public function getUsername(): string;

    public function setUsername(string $username): void;

    public function getPort(): int;

    public function setPort(int $port): void;

    public function getTimeout(): int;

    public function setTimeout(int $timeout): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getPrivateKey(): ?string;

    public function setPrivateKey(?string $privateKey): void;
}

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

use Symfony\Component\Uid\Uuid;

interface NodeInterface
{
    public function getId(): ?Uuid;

    public function setNickname(string $name): self;

    public function getNickname(): ?string;

    public function setHostname(string $hostname): self;

    public function getHostname(): ?string;

    public function setIpAddress(string $ipAddress): self;

    public function getIpAddress(): ?string;

    public function setMacAddress(string $macAddress): self;

    public function getMacAddress(): ?string;

    public function setNetVendor(string $netVendor): self;

    public function getNetVendor(): ?string;

    public function setDraft(true $draft): self;

    public function isDraft(): bool;
}

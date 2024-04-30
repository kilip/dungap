<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Device;

use Symfony\Component\Uid\Uuid;

interface DeviceInterface
{
    public function getId(): ?Uuid;

    public function setName(string $nickname): self;

    public function getName(): ?string;

    public function setNotes(string $notes): self;

    public function getNotes(): ?string;

    public function setCategory(CategoryInterface $category): self;

    public function getCategory(): ?CategoryInterface;

    public function setHostname(string $hostname): self;

    public function getHostname(): ?string;

    public function setIpAddress(string $ipAddress): self;

    public function getIpAddress(): ?string;

    public function setMacAddress(string $macAddress): self;

    public function getMacAddress(): ?string;

    public function setDraft(true $draft): self;

    public function isDraft(): bool;
}

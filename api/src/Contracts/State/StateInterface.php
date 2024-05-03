<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\State;

use Symfony\Component\Uid\Uuid;

interface StateInterface
{
    public function getId(): ?int;

    public function setEntityId(Uuid $entityId): void;

    public function getEntityId(): Uuid;

    public function setName(string $name): void;

    public function getName(): string;

    public function setState(string $state): void;

    public function getState(): string;

    public function setRelId(?Uuid $relId): void;

    public function getRelId(): ?Uuid;

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void;

    public function getUpdatedAt(): \DateTimeImmutable;
}

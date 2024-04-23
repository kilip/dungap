<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Setting;

use Symfony\Component\Uid\Uuid;

interface SettingInterface
{
    public function setKey(string $key): self;

    public function getKey(): string;

    public function setValue(SettingInterface $value): self;

    public function getValue(): object;

    public function setRelId(Uuid $relId): self;

    public function getRelId(): ?Uuid;
}

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

interface NodeInterface extends IdentifiableInterface
{
    public function setName(string $name): void;

    public function getName(): string;

    public function setIp(?string $ip): void;

    public function getIp(): ?string;

    public function setMac(?string $mac): void;

    public function getMac(): ?string;

    public function setNote(?string $note): void;

    public function getNote(): ?string;
}

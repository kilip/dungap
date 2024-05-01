<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Contracts;

use Dungap\Contracts\Service\ServiceInterface;
use Symfony\Component\Uid\Uuid;

interface GossConfigInterface
{
    public function getId(): ?Uuid;

    public function setService(ServiceInterface $service): void;

    public function getService(): ServiceInterface;

    public function setType(string $type): void;

    public function getType(): string;

    public function setTimeout(int $timeout): void;

    public function getTimeout(): int;
}

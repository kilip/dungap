<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Service;

use Dungap\Contracts\Device\DeviceInterface;
use Symfony\Component\Uid\Uuid;

interface ServiceInterface
{
    public function getId(): ?Uuid;

    public function setPort(int $port): void;

    public function getPort(): int;

    public function setDevice(DeviceInterface $device): void;

    public function getDevice(): DeviceInterface;
}

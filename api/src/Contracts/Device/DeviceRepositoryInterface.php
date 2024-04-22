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

interface DeviceRepositoryInterface
{
    public function findByMacAddress(string $macAddress): ?DeviceInterface;

    public function findByIpAddress(string $ipAddress): ?DeviceInterface;

    public function findByHostname(string $hostname): ?DeviceInterface;

    public function create(): DeviceInterface;

    public function store(DeviceInterface $device): void;
}

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
    public function findById(string $id): ?DeviceInterface;

    public function findByMacAddress(string $macAddress): ?DeviceInterface;

    public function findByIpAddress(string $ipAddress): ?DeviceInterface;

    public function findByName(string $name): ?DeviceInterface;

    public function findByHostname(string $hostname): ?DeviceInterface;

    /**
     * Find by device by ip first.
     * If still not found then find by device name.
     */
    public function findByIpOrName(string $ip, string $name): ?DeviceInterface;

    public function create(): DeviceInterface;

    public function store(DeviceInterface $device): void;
}

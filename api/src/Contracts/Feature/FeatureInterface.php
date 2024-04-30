<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Feature;

use Dungap\Contracts\Device\DeviceInterface;

interface FeatureInterface
{
    public function setFeature(string $feature): void;

    public function getFeature(): string;

    public function setDevice(DeviceInterface $device): void;

    public function getDevice(): DeviceInterface;

    public function setDriver(string $driver): void;

    public function getDriver(): string;

    /**
     * @param array<string, mixed> $setting
     */
    public function setSetting(array $setting): void;

    /**
     * @return array<string, mixed>
     */
    public function getSetting(): array;
}

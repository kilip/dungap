<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Config;

use Dungap\Contracts\Setting\ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var iterable<Scanner>
     */
    private iterable $scanners = [];

    /**
     * @var iterable<Device>
     */
    private iterable $devices = [];

    public function addScanner(Scanner $scanner): void
    {
        $this->scanners[] = $scanner;
    }

    public function addDevice(Device $device): void
    {
        $this->devices[] = $device;
    }

    /**
     * @param iterable<Device> $devices
     */
    public function setDevices(iterable $devices): void
    {
        $this->devices = $devices;
    }

    public function getDevices(): iterable
    {
        return $this->devices;
    }

    /**
     * @param iterable<Scanner> $scanners
     */
    public function setScanners(iterable $scanners): void
    {
        $this->scanners = $scanners;
    }

    public function getScanners(): iterable
    {
        if (empty($this->scanners)) {
            $this->addScanner(new Scanner(22));
        }

        return $this->scanners;
    }
}

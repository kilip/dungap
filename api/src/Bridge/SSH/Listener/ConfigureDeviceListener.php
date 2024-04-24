<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Listener;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Device\DeviceConstant;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DeviceConstant::EventConfigureDevice)]
class ConfigureDeviceListener
{
    /**
     * @codeCoverageIgnore
     */
    public function __invoke(DeviceInterface $device): void
    {
        $connection = @fsockopen($device->getIpAddress(), 22, $errno, $errstr, 5);

        if (is_resource($connection)) {
            $device->addFeature(EnumDeviceFeature::SSH);
            $device->addFeature(EnumDeviceFeature::Uptime);
            $device->addFeature(EnumDeviceFeature::PowerOff);

            @fclose($connection);
        }
    }
}

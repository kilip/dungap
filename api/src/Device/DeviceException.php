<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device;

class DeviceException extends \Exception
{
    public static function powerOnNonExistingDevice(string $deviceId): self
    {
        return new self(sprintf(
            'Failed to power on, device with id "%s" does not exist',
            $deviceId
        ));
    }
}

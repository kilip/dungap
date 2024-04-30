<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Enum;

enum EnumDeviceFeature: string
{
    case PowerOff = 'PowerOff';
    case PowerOn = 'PowerOn';
    case Reboot = 'Reboot';
    case Uptime = 'Uptime';
    case SSH = 'SSH';
}
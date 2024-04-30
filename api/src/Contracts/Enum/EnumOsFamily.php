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

enum EnumOsFamily: string
{
    case Windows = 'Windows';
    case Mac = 'Mac';
    case Ubuntu = 'Ubuntu';
    case Debian = 'Debian';
    case RouterBoard = 'RouterBoard';
    case RedHat = 'RedHat';
    case Tasmota = 'Tasmota';
}

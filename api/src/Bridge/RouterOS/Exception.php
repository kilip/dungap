<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS;

class Exception extends \Exception
{
    public static function failToWakeOnLan(string $macAddress, string $error): self
    {
        return new self(sprintf(
            'Failed to wake on LAN: "%s". Error: %s',
            $macAddress,
            $error
        ));
    }
}

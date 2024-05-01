<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Setting;

use Dungap\Setting\Config\Device;
use Dungap\Setting\Config\Scanner;

interface ConfigInterface
{
    /**
     * @return iterable<Device>
     */
    public function getDevices(): iterable;

    /**
     * @return iterable<Scanner>
     */
    public function getScanners(): iterable;
}

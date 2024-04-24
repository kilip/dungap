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

use Dungap\Device\DTO\ResultDevice;

interface OnlineCheckerInterface
{
    /**
     * @return array<int,ResultDevice>
     */
    public function run(): array;
}

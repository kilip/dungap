<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Story;

use Dungap\Tests\Factory\DeviceFactory;
use Zenstruck\Foundry\Story;

final class DefaultDeviceStory extends Story
{
    public function build(): void
    {
        DeviceFactory::createMany(100);
    }
}

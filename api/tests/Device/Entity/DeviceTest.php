<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Entity;

use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Device\Entity\Device;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    public function testFeatures(): void
    {
        $entity = new Device();

        $entity->addFeature(EnumDeviceFeature::PowerOn);
        $entity->addFeature(EnumDeviceFeature::PowerOff);
        $this->assertTrue($entity->hasFeature(EnumDeviceFeature::PowerOn));
        $this->assertTrue($entity->hasFeature(EnumDeviceFeature::PowerOff));

        $entity->removeFeature(EnumDeviceFeature::PowerOff);
        $this->assertFalse($entity->hasFeature(EnumDeviceFeature::PowerOff));
    }
}

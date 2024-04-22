<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Repository;

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Device\Repository\DeviceRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeviceRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
    }

    public function testStore(): void
    {
        /**
         * @var DeviceRepositoryInterface $repository
         */
        $repository = $this->getContainer()->get(DeviceRepository::class);
        $device = $repository->findByIpAddress('ip');

        if (!$device instanceof DeviceInterface) {
            $device = $repository->create();
        }
        $device->setIpAddress('ip');

        $device->addFeature(EnumDeviceFeature::PowerOn);
        $repository->store($device);

        $this->assertNotNull($device->getId());
        $this->assertTrue($device->hasFeature(EnumDeviceFeature::PowerOn));
        $this->assertFalse($device->hasFeature(EnumDeviceFeature::PowerOff));

        $device->addFeature(EnumDeviceFeature::PowerOff);
        $repository->store($device);

        $this->assertTrue($device->hasFeature(EnumDeviceFeature::PowerOff));
    }
}

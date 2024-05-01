<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Goss\Functional;

use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Device\Entity\Device;
use Dungap\Tests\Concern\ContainerConcern;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\ExecutableFinder;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @covers \Dungap\Device\Repository\DeviceRepository
 * @covers \Dungap\Service\Repository\ServiceRepository
 * @covers \Dungap\Bridge\Goss\Repository\GossConfigRepository
 */
class ServiceScannerFunctionalTest extends KernelTestCase
{
    use ResetDatabase;
    use ContainerConcern;

    public function setUp(): void
    {
        $executable = new ExecutableFinder();
        $goss = $executable->find('goss');
        if (!is_file($goss) && !is_executable($goss)) {
            $this->markTestSkipped('No executable goss file found');
        }

        static::bootKernel();
    }

    public function testScan(): void
    {
        $devices = $this->getService(DeviceRepositoryInterface::class);

        $github = new Device();
        $github->setName('github');
        $github->setIpAddress('20.205.243.166');

        $devices->store($github);

        $this->assertNotNull($github->getId());

        $scanner = $this->getService(ServiceScannerInterface::class);
        $scanner->scan([$github]);

        $services = $this->getService(ServiceRepositoryInterface::class);
        $gossConfigs = $this->getService(GossConfigRepositoryInterface::class);

        $this->assertNotNull($service = $services->findByPort($github->getId(), 22));
        $this->assertNotNull($gossConfigs->findByService($service));
    }
}

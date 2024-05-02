<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Goss\Report;

use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\Report\ReportFactory;
use Dungap\Device\Entity\Device;
use Dungap\Service\Entity\Service;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Dungap\Bridge\Goss\Report\ReportFactory
 * @covers \Dungap\Bridge\Goss\Report\Report
 * @covers \Dungap\Bridge\Goss\Report\Result
 */
class ReportFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $json = file_get_contents(__DIR__.'/fixtures/results.json');
        $factory = new ReportFactory();
        $report = $factory->create($json);

        $this->assertInstanceOf(GossReportInterface::class, $report);
        $this->assertNotNull($report->getSummary());
        $this->assertTrue($report->getResults()[0]->isSuccessful());

        $device = new Device();
        $device->setIpAddress('192.168.1.1');
        $service = new Service();
        $service->setDevice($device);
        $service->setPort(22);

        $this->assertNotNull($report->findByService($service));
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigFileInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\Contracts\GossResultInterface;
use Dungap\Bridge\Goss\Contracts\GossServiceValidatorInterface;
use Dungap\Bridge\Goss\Service\ServiceScanner;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Setting\ConfigFactoryInterface;
use Dungap\Contracts\Setting\ConfigInterface;
use Dungap\Setting\Config\Scanner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class ServiceScannerTest extends TestCase
{
    private MockObject|ServiceRepositoryInterface $serviceRepository;
    private MockObject|DeviceInterface $device;
    private MockObject|ConfigInterface $config;
    private MockObject|GossConfigFactoryInterface $gossConfigFactory;
    private MockObject|GossConfigFileInterface $configFile;

    private MockObject|ServiceInterface $service;
    private MockObject|GossConfigInterface $gossConfig;
    private MockObject|GossConfigRepositoryInterface $gossRepository;
    private MockObject|LoggerInterface $logger;
    private MockObject|GossReportInterface $report;
    private ServiceScanner $serviceScanner;

    private MockObject|GossServiceValidatorInterface $goss;

    protected function setUp(): void
    {
        $this->serviceRepository = $this->createMock(ServiceRepositoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $configFactory = $this->createMock(ConfigFactoryInterface::class);
        $this->gossConfig = $this->createMock(GossConfigInterface::class);
        $this->service = $this->createMock(ServiceInterface::class);
        $this->gossConfigFactory = $this->createMock(GossConfigFactoryInterface::class);
        $this->configFile = $this->createMock(GossConfigFileInterface::class);
        $this->gossRepository = $this->createMock(GossConfigRepositoryInterface::class);
        $this->report = $this->createMock(GossReportInterface::class);
        $this->goss = $this->createMock(GossServiceValidatorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->config = $this->createMock(ConfigInterface::class);

        $this->device->method('getId')
            ->willReturn(Uuid::v4());

        $this->serviceScanner = new ServiceScanner(
            $configFactory,
            $this->serviceRepository,
            $this->gossConfigFactory,
            $this->gossRepository,
            $this->goss,
            $this->logger
        );

        $configFactory->method('create')
            ->willReturn($this->config);
    }

    public function testRun(): void
    {
        $this->config->expects($this->once())
            ->method('getScanners')
            ->willReturn([
                new Scanner(22),
                new Scanner(80),
            ]);

        $this->service->expects($this->exactly(2))
            ->method('setPort');

        $this->service->expects($this->atLeastOnce())
            ->method('setDevice')
            ->with($this->device);

        $this->gossRepository->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->gossConfig);

        $this->gossConfig->expects($this->exactly(2))
            ->method('setType')
            ->with(Constant::ValidatorTypeAddress);
        $this->gossConfig->expects($this->exactly(2))
            ->method('setTimeout')
            ->with(500)
        ;
        $this->serviceRepository->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->service);

        $this->gossConfigFactory->expects($this->once())
            ->method('create')
            ->with([$this->gossConfig, $this->gossConfig])
            ->willReturn($this->configFile);

        $this->goss->expects($this->once())
            ->method('validate')
            ->with($this->configFile)
            ->willReturn($this->report)
        ;

        $result = $this->createMock(GossResultInterface::class);
        $result->expects($this->exactly(2))
            ->method('isSuccessful')
            ->willReturn(true, true);

        $this->report->expects($this->exactly(2))
            ->method('findByService')
            ->willReturn($result, $result);

        // also test when $serviceRepository throws exception
        $this->serviceRepository->expects($this->exactly(2))
            ->method('register')
            ->with($this->isInstanceOf(ServiceInterface::class))
            ->will($this->returnCallback(function () {
                static $counter = 1;
                if ($counter > 1) {
                    throw new \Exception('test exception');
                }
                ++$counter;
            }));

        $this->gossRepository->expects($this->exactly(2))
            ->method('register')
            ->with($this->isInstanceOf(GossConfigInterface::class))
            ->will($this->returnCallback(function () {
                static $counter = 1;
                if ($counter > 1) {
                    throw new \Exception('test exception');
                }
                ++$counter;
            }))
        ;

        $this->logger->expects($this->exactly(2))
            ->method('error');

        $this->serviceScanner->scan([$this->device]);
    }

    public function testRunWithError(): void
    {
        $this->config->expects($this->once())
            ->method('getScanners')
            ->willThrowException(new \Exception('test exception'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->serviceScanner->scan([$this->device]);
    }
}

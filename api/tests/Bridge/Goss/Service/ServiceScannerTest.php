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
use Dungap\Bridge\Goss\Contracts\GossInterface;
use Dungap\Bridge\Goss\Service\ServiceScanner;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Setting\ConfigInterface\ConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ServiceScannerTest extends TestCase
{
    private MockObject|ServiceRepositoryInterface $serviceRepository;
    private MockObject|DeviceInterface $device;
    private MockObject|ConfigInterface $config;
    private MockObject|GossConfigFactoryInterface $configFactory;
    private MockObject|GossConfigFileInterface $configFile;

    private MockObject|ServiceInterface $service;
    private MockObject|GossConfigInterface $gossConfig;
    private MockObject|GossConfigRepositoryInterface $gossRepository;
    private ServiceScanner $serviceScanner;

    private MockObject|GossInterface $goss;

    protected function setUp(): void
    {
        $this->serviceRepository = $this->createMock(ServiceRepositoryInterface::class);
        $this->device = $this->createMock(DeviceInterface::class);
        $this->config = $this->createMock(ConfigInterface::class);
        $this->gossConfig = $this->createMock(GossConfigInterface::class);
        $this->service = $this->createMock(ServiceInterface::class);
        $this->configFactory = $this->createMock(GossConfigFactoryInterface::class);
        $this->configFile = $this->createMock(GossConfigFileInterface::class);
        $this->gossRepository = $this->createMock(GossConfigRepositoryInterface::class);
        $this->goss = $this->createMock(GossInterface::class);

        $this->serviceScanner = new ServiceScanner(
            $this->config,
            $this->serviceRepository,
            $this->configFactory,
            $this->gossRepository,
            $this->goss
        );
    }

    public function testRun(): void
    {
        $this->config->expects($this->once())
            ->method('getScanner')
            ->willReturn([
                ['port' => 22, 'timeout' => 500],
                ['port' => 80, 'timeout' => 500],
            ]);
        $this->serviceRepository->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->service);

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

        $this->configFactory->expects($this->once())
            ->method('create')
            ->with([$this->gossConfig, $this->gossConfig])
            ->willReturn($this->configFile);

        $this->goss->expects($this->once())
            ->method('run')
            ->with($this->configFile);

        $this->serviceScanner->scan($this->device);
    }
}

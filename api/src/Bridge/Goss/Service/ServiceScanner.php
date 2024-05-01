<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Service;

use Dungap\Bridge\Goss\Constant;
use Dungap\Bridge\Goss\Contracts\GossConfigFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Bridge\Goss\Contracts\GossServiceValidatorInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Contracts\Setting\ConfigFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: ServiceScannerInterface::class, public: true)]
final readonly class ServiceScanner implements ServiceScannerInterface
{
    public function __construct(
        private ConfigFactoryInterface $config,
        private ServiceRepositoryInterface $serviceRepository,
        private GossConfigFactoryInterface $configFactory,
        private GossConfigRepositoryInterface $gossRepository,
        private GossServiceValidatorInterface $goss,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param iterable<DeviceInterface> $devices
     */
    public function scan(iterable $devices): void
    {
        try {
            $configs = $this->generateConfigs($devices);
            $configFile = $this->configFactory->create($configs);
            $output = $this->goss->validate($configFile);
            $this->processOutput($configs, $output);
        } catch (\Exception $e) {
            $this->logger->error(
                'Error while scanning service. Error: {0}',
                [$e->getMessage()]
            );
        }
    }

    /**
     * @param iterable<DeviceInterface> $devices
     *
     * @return iterable<GossConfigInterface>
     */
    private function generateConfigs(iterable $devices): iterable
    {
        $configs = [];
        foreach ($devices as $device) {
            $this->generateDeviceConfig($configs, $device);
        }

        return $configs;
    }

    /**
     * @param iterable<GossConfigInterface> $configs
     */
    private function generateDeviceConfig(iterable &$configs, DeviceInterface $device): void
    {
        $config = $this->config->create();
        foreach ($config->getScanners() as $item) {
            $service = $this->serviceRepository->create();
            $service->setPort($item->port);
            $service->setDevice($device);

            $gossConfig = $this->gossRepository->create();
            $gossConfig->setService($service);
            $gossConfig->setTimeout($item->timeout);
            $gossConfig->setType(Constant::ValidatorTypeAddress);
            $configs[] = $gossConfig;
        }
    }

    /**
     * @param array<int,GossConfigInterface> $configs
     */
    private function processOutput(array $configs, GossReportInterface $output): void
    {
        foreach ($configs as $config) {
            if ($output->hasResult($config)) {
                $this->registerService($config);
            }
        }
    }

    private function registerService(GossConfigInterface $config): void
    {
        try {
            $this->serviceRepository->register($config->getService());
            $this->gossRepository->register($config);
        } catch (\Exception $e) {
            $this->logger->error('Error while registering service for: {0} port: {1}. Error: {2}', [
                $config->getService()->getDevice(),
                $config->getService()->getPort(),
                $e->getMessage(),
            ]);
        }
    }
}

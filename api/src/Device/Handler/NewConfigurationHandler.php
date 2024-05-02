<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Handler;

use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Contracts\Device\CategoryInterface;
use Dungap\Contracts\Device\CategoryRepositoryInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Service\Command\ConfigureValidatorCommand;
use Dungap\Setting\Command\NewConfigurationCommand;
use Dungap\Setting\Config\Device;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class NewConfigurationHandler
{
    public function __construct(
        private DeviceRepositoryInterface $devices,
        private CategoryRepositoryInterface $categories,
        #[Autowire('@dungap.service_scanner')]
        private ServiceScannerInterface $serviceScanner,
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(NewConfigurationCommand $command): void
    {
        $config = $command->config;
        $devices = [];
        try {
            foreach ($config->getDevices() as $deviceConfig) {
                $device = $this->processDevice($deviceConfig);
                if (!is_null($device)) {
                    $devices[] = $device;
                }
            }
            $this->serviceScanner->scan($devices);
            $this->messageBus->dispatch(new ConfigureValidatorCommand);
        } catch (\Exception $e) {
            $this->logger->error('Error while try to handle new configuration: {0}', [$e->getMessage()]);
        }
    }

    private function processDevice(Device $deviceConfig): ?DeviceInterface
    {
        $device = $this->devices->findByIpOrName($deviceConfig->name, $deviceConfig->ip);
        try {
            $category = $this->loadCategory($deviceConfig->category);
            if (is_null($device)) {
                $device = $this->devices->create();
            }
            $device->setName($deviceConfig->name);
            $device->setIpAddress($deviceConfig->ip);
            $device->setHostname($deviceConfig->hostname);
            $device->setMacAddress($deviceConfig->mac);
            $device->setCategory($category);
            $this->devices->store($device);
        } catch (\Exception $e) {
            $this->logger->error('Error while try to handle configuration for device {0}: {1}', [
                $deviceConfig->name,
                $e->getMessage(),
            ]);
        }

        return $device;
    }

    private function loadCategory(string $category = null): CategoryInterface
    {
        $category = $category ?? 'Uncategorized';

        return $this->categories->findOrCreate($category);
    }
}

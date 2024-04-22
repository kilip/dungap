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

use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\DeviceScannerInterface;
use Dungap\Device\Command\ScanDeviceCommand;
use Dungap\Device\DeviceConstant;
use Dungap\Device\DTO\ResultDevice;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class ScanDeviceHandler
{
    public function __construct(
        private DeviceScannerInterface $scanner,
        private DeviceRepositoryInterface $deviceRepository,
        private EventDispatcherInterface $dispatcher,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function __invoke(ScanDeviceCommand $command): void
    {
        $this->logger?->notice('start scanning network devices... ', $command->target);

        $results = $this->scanner->scan($command);

        foreach ($results as $result) {
            $device = $this->loadDevice($result);
            $device->setIpAddress($result->ipAddress)
                ->setMacAddress($result->macAddress)
                ->setHostname($result->hostname)
                ->setNetVendor($result->vendor);

            $this->dispatcher->dispatch($device, DeviceConstant::EventDeviceFound);
            $this->deviceRepository->store($device);
        }
    }

    private function loadDevice(ResultDevice $resultDevice): DeviceInterface
    {
        $repository = $this->deviceRepository;

        if (
            !is_null($resultDevice->macAddress)
            && !is_null($device = $repository->findByMacAddress($resultDevice->macAddress))) {
            return $device;
        }

        if (!is_null($device = $repository->findByIpAddress($resultDevice->ipAddress))) {
            return $device;
        }

        if (!is_null($resultDevice->hostname) && !is_null($device = $repository->findByHostname($resultDevice->hostname))) {
            return $device;
        }

        return $repository->create()->setDraft(true);
    }
}

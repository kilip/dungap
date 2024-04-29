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
use Dungap\Device\Command\PowerOnCommand;
use Dungap\Device\DeviceConstant;
use Dungap\Device\DeviceException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class PowerOnHandler
{
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        private EventDispatcherInterface $dispatcher,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(PowerOnCommand $command): void
    {
        $device = $this->deviceRepository->findById($command->deviceId);

        if (!$device instanceof DeviceInterface) {
            throw DeviceException::powerOnNonExistingDevice($command->deviceId);
        }
        $this->logger?->notice('turning on device "{0}"', [$device->getName()]);

        $this->dispatcher->dispatch($device, DeviceConstant::EventDevicePowerOn);
    }
}

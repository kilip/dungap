<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Feature\Handler;

use Dungap\Contracts\Enum\EnumDeviceFeature;
use Dungap\Contracts\Feature\FeatureRepositoryInterface;
use Dungap\Contracts\Feature\PowerOffDriverInterface;
use Dungap\Device\Command\PowerOffCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PowerOffHandler
{
    /**
     * @param iterable<PowerOffDriverInterface> $drivers
     */
    public function __construct(
        private FeatureRepositoryInterface $features,
        #[TaggedIterator('dungap.drivers.power_off')]
        private iterable $drivers,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function __invoke(PowerOffCommand $command): void
    {
        try {
            $deviceId = $command->deviceId;
            $this->handle($deviceId);
        } catch (\Exception $e) {
            $this->logger->error('error while powering off device. Error: {0}', [$e->getMessage()]);
        }
    }

    private function handle(string $deviceId): void
    {
        $feature = $this->features->findByDevice($deviceId, EnumDeviceFeature::PowerOff->value);
        $device = $feature->getDevice();
        $driver = null;

        foreach ($this->drivers as $item) {
            if ($feature->getDriver() == $item->getName()) {
                $driver = $item;
                break;
            }
        }

        if (is_null($driver)) {
            $this->logger?->warning('[dungap] power off driver not available for {0}', [$device->getName()]);

            return;
        }

        $this->logger?->notice('[dungap] powering off {0}', [$device->getName()]);

        if (!$driver->process($feature)) {
            $this->logger?->warning('[dungap] failed powering off {0}', [$device->getName()]);
        } else {
            $this->logger->notice('[dungap] successfully powering off {0}', [$device->getName()]);
        }
    }
}

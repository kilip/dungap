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

use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Contracts\Device\PowerOffProcessorInterface;
use Dungap\Device\Command\PowerOffCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PowerOffHandler
{
    /**
     * @param iterable<int,PowerOffProcessorInterface> $processors
     */
    public function __construct(
        private DeviceRepositoryInterface $deviceRepository,
        #[TaggedIterator('dungap.processor.power_off')]
        private iterable $processors,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function __invoke(PowerOffCommand $command): void
    {
        $device = $this->deviceRepository->findById($command->deviceId);

        foreach ($this->processors as $processor) {
            $processed = false;
            if ($processor->supports($device)) {
                $processed = $processor->process($device);
            }

            if ($processed) {
                $this->logger?->info('Power Off processed by {0}', [get_class($processor)]);
                break;
            }
        }
    }
}

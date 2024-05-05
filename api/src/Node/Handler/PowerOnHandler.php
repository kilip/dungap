<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Handler;

use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\PowerOnProcessorInterface;
use Dungap\Dungap;
use Dungap\Node\Command\PowerOnCommand;
use Dungap\Node\NodeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PowerOnHandler
{
    /**
     * @param iterable<PowerOnProcessorInterface> $processors
     */
    public function __construct(
        private FeatureRepositoryInterface $features,
        #[TaggedIterator(Dungap::PowerOnProcessorTag)]
        private iterable $processors,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(PowerOnCommand $command): void
    {
        $feature = $this->features->findByFeature($command->deviceId, Dungap::PowerOnFeature);

        if (!$feature instanceof FeatureInterface) {
            $this->logger->error('No feature found for node with id: {0}', [$command->deviceId]);

            return;
        }

        try {
            $this->process($feature);
        } catch (\Exception $e) {
            $this->logger->error(
                'Failed to power on node {0}. Error: {1}', [
                    $feature->getNode()->getName(),
                    $e->getMessage(),
                ]
            );
        }
    }

    private function process(FeatureInterface $feature): void
    {
        $processor = $this->findProcessor($feature);

        if (!$processor instanceof PowerOnProcessorInterface) {
            throw NodeException::powerOnProcessorInvalid($feature);
        }

        $processor->process($feature);
    }

    private function findProcessor(FeatureInterface $feature): ?PowerOnProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->getDriverName() === $feature->getDriver()) {
                return $processor;
            }
        }

        return null;
    }
}

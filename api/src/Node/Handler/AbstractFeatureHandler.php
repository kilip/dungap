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
use Dungap\Contracts\Node\FeatureProcessorInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Node\NodeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

abstract class AbstractFeatureHandler
{
    /**
     * @param iterable<FeatureProcessorInterface> $processors
     */
    public function __construct(
        protected FeatureRepositoryInterface $features,
        protected iterable $processors,
        protected LoggerInterface $logger
    ) {
    }

    protected function process(Uuid $deviceId, string $feature): void
    {
        $feature = $this->features->findByFeature($deviceId, $feature);

        if (!$feature instanceof FeatureInterface) {
            $this->logger->error('No feature {0} found for node with id: {1}', [$feature, $deviceId]);

            return;
        }

        try {
            $this->doProcess($feature);
        } catch (\Exception $e) {
            $this->logger->error(
                'Failed to {0} node {1}. Error: {2}', [
                    $feature->getName(),
                    $feature->getNode()->getName(),
                    $e->getMessage(),
                ]
            );
        }
    }

    private function findProcessor(FeatureInterface $feature): ?FeatureProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->getDriverName() === $feature->getDriver()) {
                return $processor;
            }
        }

        return null;
    }

    private function doProcess(FeatureInterface $feature): void
    {
        $processor = $this->findProcessor($feature);

        if (is_null($processor)) {
            throw NodeException::powerOnProcessorInvalid($feature);
        }

        $processor->process($feature);
    }
}

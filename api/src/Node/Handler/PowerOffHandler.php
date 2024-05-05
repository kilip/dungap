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

use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\PowerOffProcessorInterface;
use Dungap\Dungap;
use Dungap\Node\Command\PowerOffCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PowerOffHandler extends AbstractFeatureHandler
{
    /**
     * @param iterable<PowerOffProcessorInterface> $processors
     */
    public function __construct(
        protected FeatureRepositoryInterface $features,
        #[TaggedIterator(Dungap::PowerOffProcessorTag)]
        protected iterable $processors,
        protected LoggerInterface $logger
    ) {
        parent::__construct($this->features, $this->processors, $this->logger);
    }

    public function __invoke(PowerOffCommand $command): void
    {
        $this->process($command->deviceId, Dungap::PowerOffFeature);
    }
}

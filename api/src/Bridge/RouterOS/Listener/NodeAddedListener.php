<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Listener;

use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Dungap;
use Dungap\Node\Event\NodeAddedEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Dungap::OnNodeAdded)]
final readonly class NodeAddedListener
{
    public function __construct(
        private FeatureRepositoryInterface $features,
        #[Autowire('%dungap.node.processor.power_on%')]
        private string $powerOnProcessor
    ) {
    }

    public function __invoke(NodeAddedEvent $event): void
    {
        if (Dungap::RouterOsDriver !== $this->powerOnProcessor) {
            return;
        }

        $node = $event->node;
        $feature = $this->features->findByFeature($node->getId(), Dungap::PowerOnFeature);

        if (!$feature instanceof FeatureInterface) {
            $feature = $this->features->create();
            $feature->setNode($node);
            $feature->setDriver(Dungap::RouterOsDriver);
            $feature->setName(Dungap::PowerOnFeature);
            $this->features->save($feature);
        }
    }
}

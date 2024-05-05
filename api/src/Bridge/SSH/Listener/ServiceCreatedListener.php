<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Listener;

use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Dungap;
use Dungap\Service\Event\ServiceCreatedEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Dungap::OnServiceCreated)]
final readonly class ServiceCreatedListener
{
    public function __construct(
        private FeatureRepositoryInterface $features,
        #[Autowire('%dungap.node.processor.reboot%')]
        private string $defaultRebootDriver,
        #[Autowire('%dungap.node.processor.power_off%')]
        private string $defaultPowerOffDriver,
    ) {
    }

    public function __invoke(ServiceCreatedEvent $event): void
    {
        $service = $event->service;
        if (22 !== $service->getPort()) {
            return;
        }

        if (Dungap::SshDriver === $this->defaultPowerOffDriver) {
            $this->process($event->service, Dungap::PowerOffFeature);
        }

        if (Dungap::SshDriver === $this->defaultRebootDriver) {
            $this->process($event->service, Dungap::RebootFeature);
        }
    }

    private function process(ServiceInterface $service, string $featureName): void
    {
        $node = $service->getNode();
        $feature = $this->features->findByFeature(
            $node->getId(),
            $featureName
        );

        if (is_null($feature)) {
            $feature = $this->features->create();
            $feature->setNode($node);
            $feature->setName($featureName);
            $feature->setDriver(Dungap::SshDriver);

            $this->features->save($feature);
        }
    }
}

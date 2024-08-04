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

use Dungap\Bridge\SSH\Contracts\NodeExporterInterface;
use Dungap\Bridge\SSH\Contracts\SshFactoryInterface;
use Dungap\Bridge\SSH\Contracts\SshInterface;
use Dungap\Bridge\SSH\SSH;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Dungap;
use Dungap\State\Event\StateUpdatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Dungap::OnStateUpdated)]
final readonly class StateUpdatedListener
{
    /**
     * @param iterable<NodeExporterInterface> $exporters
     */
    public function __construct(
        private SshFactoryInterface $factory,
        #[TaggedIterator(SSH::ExporterServiceTag)]
        private iterable $exporters,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(StateUpdatedEvent $event): void
    {
        $regex = '#(service)\..*\.(.*)$#';
        $name = $event->name;
        /** @var NodeInterface $node */
        $node = $event->related;

        // only process on service state changed
        if (preg_match_all($regex, $name, $matches) === 1 && $node->getExporter() === Dungap::NodeExporterSSH) {

            /** @var ServiceInterface $service $service */
            $service = $event->entity;

            $ssh = $this->factory->createSshClient($node);
            $isLoggedIn = $ssh->login();

            // only process on ssh port
            if (
                $ssh->getConfig()->port === $service->getPort()
                && $isLoggedIn
            ) {
                $this->logger->info('start exporting {0} info', [$node->getName()]);
                $this->process($node, $ssh);
            }
        }
    }

    private function process(
        NodeInterface $node,
        SshInterface $ssh
    ): void {
        foreach ($this->exporters as $exporter) {
            try {
                $exporter->process($node, $ssh);
            } catch (\Exception $e) {
                $this->logger->error(
                    'Failed to ssh export node info {0}. Error: {1}',
                    [
                        $node->getName(),
                        $e->getMessage(),
                    ]
                );
            }
        }
    }
}

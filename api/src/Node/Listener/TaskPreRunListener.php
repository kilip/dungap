<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Listener;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Dungap;
use Dungap\Node\Config\Config;
use Dungap\Node\Config\Host;
use Dungap\Node\Event\NodeAddedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(event: Dungap::OnTaskPreRun)]
final class TaskPreRunListener
{
    /**
     * @var iterable<NodeInterface>
     */
    private iterable $addedNodes = [];

    public function __construct(
        private readonly Config $config,
        private readonly NodeRepositoryInterface $nodes,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $hosts = $this->config->getHosts();

        foreach ($hosts as $host) {
            try {
                $this->process($host);
            } catch (\Exception $e) {
                $this->logger->error('Error while processing device import: {0}. Error: ', [
                    $host->name,
                    $e->getMessage(),
                ]);
            }
        }

        foreach ($this->addedNodes as $node) {
            try {
                $event = new NodeAddedEvent($node);
                $this->dispatcher->dispatch($event, Dungap::OnNodeAdded);
            } catch (\Exception $e) {
                $this->logger->error(
                    'Error while dispatching event {0} for Node {1}: {2}',
                    [
                        Dungap::OnNodeAdded,
                        $node->getName(),
                        $e->getMessage(),
                    ]
                );
            }
        }
    }

    private function process(Host $host): void
    {
        $node = $this->nodes->findByName($host->name);

        if (!$node instanceof NodeInterface) {
            $node = $this->nodes->create();
            $this->addedNodes[] = $node;
        }

        $node->setName($host->name);
        $node->setIp($host->ip);
        $node->setMac($host->mac);
        $node->setNote($host->note);

        $this->nodes->save($node);
    }
}

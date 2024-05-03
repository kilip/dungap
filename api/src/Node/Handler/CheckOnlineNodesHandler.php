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

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Contracts\Node\OnlineCheckerInterface;
use Dungap\Dungap;
use Dungap\Node\Command\CheckOnlineNodesCommand;
use Dungap\State\Event\StateUpdatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class CheckOnlineNodesHandler
{
    public function __construct(
        private NodeRepositoryInterface $nodes,
        private EventDispatcherInterface $dispatcher,
        private OnlineCheckerInterface $onlineChecker,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(CheckOnlineNodesCommand $command): void
    {
        $logger = $this->logger;
        $logger->info('Start checking online nodes');

        $nodes = $this->nodes->findAll();
        foreach ($nodes as $node) {
            try {
                if (!is_null($node->getIp())) {
                    $this->processNode($node);
                }
            } catch (\Exception $e) {
                $logger->error(
                    'Failed while online checking node: {0}. Error: {1}',
                    [
                        $node->getName(),
                        $e->getMessage(),
                    ]
                );
            }
        }
    }

    private function processNode(NodeInterface $node): void
    {
        $report = $this->onlineChecker->check($node);
        $state = $report->success ? Dungap::OnlineState : Dungap::OfflineState;
        $event = new StateUpdatedEvent(
            $node->getId(),
            Dungap::NodeOnlineStateName,
            $state,
        );

        $this->dispatcher->dispatch($event, Dungap::OnStateChanged);

        if ($report->success) {
            $event = new StateUpdatedEvent(
                $node->getId(),
                Dungap::NodeLatencyState,
                strval($report->latency)
            );
            $this->dispatcher->dispatch($event, Dungap::OnStateChanged);
        }
    }
}

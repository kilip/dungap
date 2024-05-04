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

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\Dungap;

#[AsEntityListener]
final readonly class NodeEntityListener
{
    public function __construct(
        private StateRepositoryInterface $states,
    ) {
    }

    public function postLoad(NodeInterface $node): void
    {
        $this->setOnline($node);
        $this->setLatency($node);
    }

    private function setOnline(NodeInterface $node): void
    {
        $online = false;
        $state = $this->states->getLastState(
            $node->getId(),
            Dungap::NodeOnlineStateName
        );
        if ($state instanceof StateInterface) {
            $online = Dungap::OnlineState === $state->getState();
        }

        $node->setOnline($online);
    }

    private function setLatency(NodeInterface $node): void
    {
        if ($node->isOnline()) {
            $latency = null;
            $state = $this->states->getLastState(
                $node->getId(),
                Dungap::NodeLatencyState
            );
            if ($state instanceof StateInterface) {
                $latency = floatval($state->getState());
            }

            $node->setLatency($latency);
        }
    }
}

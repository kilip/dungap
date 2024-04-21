<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Network\Handler;

use Dungap\Contracts\Network\NetworkScannerInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Network\Command\ScanNodesCommand;
use Dungap\Network\ResultNode;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ScanNodesHandler
{
    public function __construct(
        private NetworkScannerInterface $scanner,
        private NodeRepositoryInterface $nodeRepository,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function __invoke(ScanNodesCommand $command): void
    {
        $this->logger?->notice('start scanning nodes... ', $command->getTarget());

        $results = $this->scanner->scan($command->getTarget());

        foreach ($results as $result) {
            $node = $this->loadNode($result);
            $node->setIpAddress($result->ipAddress)
                ->setMacAddress($result->macAddress)
                ->setHostname($result->hostname)
                ->setNetVendor($result->vendor);
            $this->nodeRepository->store($node);
        }
    }

    private function loadNode(ResultNode $resultNode): NodeInterface
    {
        $repository = $this->nodeRepository;

        if (
            !is_null($resultNode->macAddress)
            && !is_null($node = $repository->findByMacAddress($resultNode->macAddress))) {
            return $node;
        }

        if (!is_null($node = $repository->findByIpAddress($resultNode->ipAddress))) {
            return $node;
        }

        if (!is_null($resultNode->hostname) && !is_null($node = $repository->findByHostname($resultNode->hostname))) {
            return $node;
        }

        return $repository->create()->setDraft(true);
    }
}

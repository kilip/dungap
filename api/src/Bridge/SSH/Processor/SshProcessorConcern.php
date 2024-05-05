<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Processor;

use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\SSH\SshFactoryInterface;
use Dungap\Dungap;
use Psr\Log\LoggerInterface;

trait SshProcessorConcern
{
    abstract protected function getCommand(): string;

    public function __construct(
        private readonly SshFactoryInterface $sshFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getDriverName(): string
    {
        return Dungap::SshDriver;
    }

    public function process(FeatureInterface $feature): void
    {
        $node = $feature->getNode();
        $ssh = $this->sshFactory->createSshClient($node);
        $command = $this->getCommand();

        try {
            $ssh->execute($command);
            $ssh->disconnect();
        } catch (\Exception $e) {
            $regex = '#\s+(prematurely).*#';
            if (!preg_match($regex, $e->getMessage(), $matches)) {
                $this->logger->error(
                    'Failed to {0} {1}. Error {2} {3}', [
                        $feature->getName(),
                        $node->getName(),
                        $e->getMessage(),
                        $ssh->getLogs(),
                    ]
                );
            } else {
                $this->logger->info(
                    'Successfully {0} {1}.', [
                        $feature->getName(),
                        $node->getName(),
                    ]
                );
            }
        }

        $this->logger->debug('SSH Log: {0}', $ssh->getLogs());
    }
}

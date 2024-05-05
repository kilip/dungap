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
use Dungap\Contracts\Node\PowerOffProcessorInterface;
use Dungap\Contracts\SSH\SshFactoryInterface;
use Psr\Log\LoggerInterface;

final readonly class PowerOffProcessor implements PowerOffProcessorInterface
{
    use SshDriverConcern;

    public function __construct(
        private SshFactoryInterface $sshFactory,
        private LoggerInterface $logger
    ) {
    }

    public function process(FeatureInterface $feature): void
    {
        $node = $feature->getNode();
        $ssh = $this->sshFactory->createSshClient($node);

        try {
            $ssh->execute('sudo poweroff');
            $ssh->disconnect();
        } catch (\Exception $e) {
            $regex = '#\s+(prematurely).*#';
            if (!preg_match($regex, $e->getMessage(), $matches)) {
                $this->logger->error(
                    'Failed to power off {0}. Error {1} {2}', [
                        $node->getName(),
                        $e->getMessage(),
                        $ssh->getLogs(),
                    ]
                );
            } else {
                $this->logger->info(
                    'Successfully power off {0}.', [
                        $node->getName(),
                    ]
                );
            }
        }

        $this->logger->debug('SSH Log: {0}', $ssh->getLogs());
    }
}

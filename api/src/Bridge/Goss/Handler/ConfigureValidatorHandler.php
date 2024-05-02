<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Handler;

use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Service\Command\ConfigureValidatorCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ConfigureValidatorHandler
{
    public function __construct(
        private GossFileFactoryInterface $fileFactory,
        private LoggerInterface $logger,
    )
    {
    }

    public function __invoke(ConfigureValidatorCommand $command): void
    {
        $this->fileFactory->configure();
        $this->logger->notice('new goss file configured');
    }
}

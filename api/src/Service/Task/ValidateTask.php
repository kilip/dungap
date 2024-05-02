<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Task;

use Dungap\Bridge\Goss\Config\FileFactory;
use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Contracts\Task\TaskInterface;
use Dungap\Service\Command\ValidateServiceCommand;
use Dungap\Task\TaskTrait;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

class ValidateTask implements TaskInterface
{
    use TaskTrait;

    public function __construct(
        private GossFileFactoryInterface $fileFactory,
        private MessageBusInterface $messageBus
    ) {
    }

    public function preRun(): void
    {
        $configFile = $this->fileFactory->getFile();
        if (!file_exists($configFile->getFileName())) {
            $this->fileFactory->configure();
        }
    }

    public function run(): void
    {
        $this->messageBus->dispatch(new ValidateServiceCommand());
    }
}

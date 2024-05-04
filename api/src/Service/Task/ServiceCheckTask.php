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

use Dungap\Contracts\Core\TaskInterface;
use Dungap\Core\Task\TaskConcern;
use Dungap\Service\Command\ServiceCheckCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class ServiceCheckTask implements TaskInterface
{
    use TaskConcern;

    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function run(): void
    {
        $command = new ServiceCheckCommand();
        $this->messageBus->dispatch($command);
    }
}

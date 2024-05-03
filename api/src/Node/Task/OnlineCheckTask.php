<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Task;

use Dungap\Contracts\Core\TaskInterface;
use Dungap\Core\Task\TaskConcern;
use Dungap\Node\Command\CheckOnlineNodesCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnlineCheckTask implements TaskInterface
{
    use TaskConcern;

    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function run(): void
    {
        $command = new CheckOnlineNodesCommand();
        $this->messageBus->dispatch($command);
    }
}

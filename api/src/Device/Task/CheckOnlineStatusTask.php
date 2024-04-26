<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Task;

use Dungap\Contracts\Task\TaskInterface;
use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Task\TaskTrait;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

final class CheckOnlineStatusTask implements TaskInterface
{
    use TaskTrait;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        #[Autowire('%kernel.project_dir%/var/task.online_status.lck')]
        private readonly string $lockfile,
    ) {
        $this->interval = 30;
    }

    public function preRun(): void
    {
        clearstatcache(true, $this->lockfile);
        if (is_file($this->lockfile)) {
            unlink($this->lockfile);
        }
    }

    public function run(): void
    {
        // avoid tasks to be run when lock file exists
        clearstatcache(true, $this->lockfile);
        if (!is_file($this->lockfile)) {
            $this->messageBus->dispatch(new CheckOnlineCommand($this->lockfile));
        }
    }
}

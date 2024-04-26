<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Task\Cli;

use Dungap\Contracts\Task\InstallInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand('dungap:install')]
class InstallCommand extends Command
{
    /**
     * @param iterable<int,InstallInterface> $tasks
     */
    public function __construct(
        #[TaggedIterator('dungap.task.install')]
        private iterable $tasks
    ) {
        parent::__construct('dungap:install');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->tasks as $task) {
            $task->run($output);
        }

        return self::SUCCESS;
    }
}

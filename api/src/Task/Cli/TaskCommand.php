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

use Carbon\CarbonInterval;
use Dungap\Contracts\TaskInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand('dungap:task')]
final class TaskCommand extends Command
{
    /**
     * @param iterable<int,TaskInterface> $tasks
     */
    public function __construct(
        #[TaggedIterator('dungap.task')]
        private readonly iterable $tasks,
        private readonly LoggerInterface $logger,
        #[Autowire('%env(APP_ENV)%')]
        private readonly string $env = 'test',
    ) {
        parent::__construct('dungap:tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $terminate = false;
        // @codeCoverageIgnoreStart
        if (function_exists('pcntl_signal')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, function () use (&$terminate, $output) {
                $output->writeln('Received SIGTERM signal');
                $terminate = true;
            });
        }
        // @codeCoverageIgnoreEnd

        $this->preRunTasks();

        while (!$terminate) {
            $this->doRunTasks($output);
            if ('test' === $this->env) {
                break;
            } else {
                sleep(1); // @codeCoverageIgnore
            }
        }

        return Command::SUCCESS;
    }

    private function preRunTasks(): void
    {
        foreach ($this->tasks as $task) {
            $task->preRun();
        }
    }

    private function doRunTasks(OutputInterface $output): void
    {
        foreach ($this->tasks as $task) {
            $this->runTask($task, $output);
        }
    }

    private function runTask(TaskInterface $task, OutputInterface $output): void
    {
        $haveToRun = false;
        $interval = $task->getInterval();
        $lastRun = $task->getLastRun();
        $now = new \DateTimeImmutable();

        if (is_null($lastRun)) {
            $haveToRun = true;
        } else {
            $interval = CarbonInterval::second($interval)->toDateInterval();
            $nextRun = $lastRun->add($interval);

            // $output->writeln(sprintf('now: %s last: %s', $now->format('H:i:s'), $nextRun->format('H:i:s')));
            if ($now >= $nextRun) {
                $haveToRun = true;
            }
        }

        try {
            if ($haveToRun) {
                $output->writeln('Task: running task '.get_class($task));
                $task->run();
                $task->setLastRun($now);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}

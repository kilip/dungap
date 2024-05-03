<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Core\Task;

use Carbon\CarbonInterval;
use Dungap\Contracts\Core\TaskInterface;
use Dungap\Contracts\Core\TaskRunnerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class TaskRunner implements TaskRunnerInterface
{
    /**
     * @param iterable<TaskInterface> $tasks
     */
    public function __construct(
        #[TaggedIterator(tag: 'dungap.task')]
        private iterable $tasks
    ) {
    }

    public function run(OutputInterface $output): void
    {
        foreach ($this->tasks as $task) {
            $this->runTask($task, $output);
        }
    }

    private function haveToRun(TaskInterface $task): bool
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
            if ($now >= $nextRun) {
                $haveToRun = true;
            }
        }

        return $haveToRun;
    }

    private function runTask(mixed $task, OutputInterface $output): void
    {
        try {
            $haveToRun = $this->haveToRun($task);
            if ($haveToRun) {
                $output->writeln(sprintf(
                    '<info>Running task </info><comment>%s</comment>',
                    get_class($task)
                ));
                $task->setLastRun(new \DateTimeImmutable());
                $task->run();
            }
        } catch (\Exception $e) {
            // $this->logger->error($e->getMessage());
            $output->writeln(sprintf(
                '<error>Error: </error><comment>%s</comment>',
                $e->getMessage()
            ));
        }
    }
}

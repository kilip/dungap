<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Core\Task;

use Carbon\Carbon;
use Dungap\Contracts\Core\TaskInterface;
use Dungap\Core\Task\TaskRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class TaskRunnerTest extends TestCase
{
    private MockObject|TaskInterface $task;
    private MockObject|OutputInterface $output;
    private TaskRunner $runner;

    public function setUp(): void
    {
        $this->task = $this->createMock(TaskInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->runner = new TaskRunner([$this->task]);

        $this->task->method('getInterval')
            ->willReturn(30);
    }

    public function testRunWithNullLastRun(): void
    {
        $this->task->expects($this->once())
            ->method('getLastRun')
            ->willReturn(null);
        $this->task->expects($this->once())
            ->method('run');

        $this->runner->run($this->output);
    }

    public function testRunWithValidInterval(): void
    {
        $date = Carbon::now()->subSeconds(31);
        $this->task->expects($this->once())
            ->method('getLastRun')
            ->willReturn($date->toDateTimeImmutable());
        $this->task->expects($this->once())
            ->method('run');

        $this->runner->run($this->output);
    }

    public function testRunWithError(): void
    {
        $this->task->expects($this->once())
            ->method('getLastRun')
            ->willReturn(null);
        $this->task->expects($this->once())
            ->method('run')
            ->willThrowException(new \Exception('test'));

        $this->output->expects($this->exactly(2))
            ->method('writeln');
        $this->runner->run($this->output);
    }
}

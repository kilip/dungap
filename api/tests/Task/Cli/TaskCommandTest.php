<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Task\Cli;

use Carbon\CarbonInterval;
use Dungap\Contracts\Task\TaskInterface;
use Dungap\Task\Cli\TaskCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskCommandTest extends TestCase
{
    private MockObject|InputInterface $input;
    private MockObject|OutputInterface $output;
    private MockObject|TaskInterface $task;
    private MockObject|LoggerInterface $logger;
    private TaskCommand $command;

    protected function setUp(): void
    {
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->task = $this->createMock(TaskInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->command = new TaskCommand(
            tasks: [$this->task],
            logger: $this->logger
        );

        $this->task->expects($this->atLeastOnce())
            ->method('getInterval')
            ->willReturn(5);
    }

    public function testExecute(): void
    {
        $this->task->expects($this->once())
            ->method('preRun');
        $this->task->expects($this->once())
            ->method('run');

        $this->command->run($this->input, $this->output);
    }

    public function testExecuteWithException(): void
    {
        $this->task->expects($this->once())
            ->method('run')
            ->willThrowException(new \Exception());
        $this->logger->expects($this->once())
            ->method('error');

        $this->command->run($this->input, $this->output);
    }

    public function testExecuteWithInvalidInterval(): void
    {
        $this->task->expects($this->never())
            ->method('run');

        $interval = CarbonInterval::second(1)->toDateInterval();
        $lastRun = (new \DateTimeImmutable())->sub($interval);
        $this->task->expects($this->once())
            ->method('getLastRun')
            ->willReturn($lastRun);

        $this->command->run($this->input, $this->output);
    }

    public function testExecuteWithValidInterval(): void
    {
        $this->task->expects($this->once())
            ->method('run');

        $lastRun = (new \DateTimeImmutable())->sub(CarbonInterval::second(30)->toDateInterval());
        $this->task->expects($this->once())
            ->method('getLastRun')
            ->willReturn($lastRun);

        $this->command->run($this->input, $this->output);
    }
}

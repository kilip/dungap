<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Task;

use Dungap\Device\Command\CheckOnlineCommand;
use Dungap\Device\Task\CheckOnlineStatusTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckOnlineStatusTaskTest extends TestCase
{
    private MockObject|MessageBusInterface $messageBus;
    private string $lockfile;
    private CheckOnlineStatusTask $task;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->lockfile = sys_get_temp_dir().'/dungap/online_status.lck';
        $this->task = new CheckOnlineStatusTask($this->messageBus, $this->lockfile);

        if (!is_dir($dir = dirname($this->lockfile))) {
            mkdir($dir, 0777, true);
        }

        if (is_file($this->lockfile)) {
            unlink($this->lockfile);
        }
    }

    public function testPreRun(): void
    {
        touch($this->lockfile);

        $this->assertFileExists($this->lockfile);
        $this->task->preRun();
        $this->assertFileDoesNotExist($this->lockfile);
    }

    public function testRun(): void
    {
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(CheckOnlineCommand::class))
            ->willReturn(new Envelope(new \stdClass()))
        ;

        $this->task->run();
    }

    public function testRunWhenLockFileExists(): void
    {
        $this->messageBus->expects($this->never())
            ->method('dispatch');

        touch($this->lockfile);

        $this->task->run();
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Task;

use Dungap\Service\Command\ServiceCheckCommand;
use Dungap\Service\Task\ServiceCheckTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ServiceCheckTaskTest extends TestCase
{
    private MockObject|MessageBusInterface $messageBus;
    private ServiceCheckTask $task;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->task = new ServiceCheckTask(
            $this->messageBus
        );
    }

    public function testRun(): void
    {
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ServiceCheckCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $this->task->run();
    }
}

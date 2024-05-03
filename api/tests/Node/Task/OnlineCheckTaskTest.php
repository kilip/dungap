<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Task;

use Dungap\Node\Command\CheckOnlineNodesCommand;
use Dungap\Node\Task\OnlineCheckTask;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OnlineCheckTaskTest extends TestCase
{
    public function testRun(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $task = new OnlineCheckTask($bus);

        $bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(CheckOnlineNodesCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $task->run();
    }
}

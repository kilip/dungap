<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Core\Cli;

use Dungap\Contracts\Core\TaskRunnerInterface;
use Dungap\Core\Cli\TaskCommand;
use Dungap\Dungap;
use Dungap\Tests\Concern\ContainerConcern;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskCommandTest extends KernelTestCase
{
    use ContainerConcern;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testRun(): void
    {
        $runner = $this->createMock(TaskRunnerInterface::class);
        $dispatcher = $this->createMock(EventDispatcher::class);
        static::getContainer()->set(TaskRunnerInterface::class, $runner);
        static::getContainer()->set(EventDispatcherInterface::class, $dispatcher);

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(TaskCommand::class), Dungap::OnTaskPreRun);

        $app = new Application(self::$kernel);
        $command = $app->find('dungap:task:run');
        $tester = new CommandTester($command);

        $runner->expects($this->once())
            ->method('run');
        $tester->execute([]);
        $tester->assertCommandIsSuccessful();
    }
}

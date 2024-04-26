<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Security\Task;

use Dungap\Contracts\User\UserRepositoryInterface;
use Dungap\Contracts\UserInterface;
use Dungap\Security\Task\SecurityInstallTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class SecurityInstallTaskTest extends TestCase
{
    private MockObject|UserRepositoryInterface $userRepository;
    private MockObject|UserInterface $user;
    private MockObject|OutputInterface $output;

    private SecurityInstallTask $task;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->task = new SecurityInstallTask(
            $this->userRepository,
            'admin',
            'admin@example.com',
            'admin'
        );
    }

    public function testRun(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('admin@example.com')
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('create')
            ->willReturn($this->user)
        ;

        $this->user->expects($this->once())
            ->method('setUsername');
        $this->user->expects($this->once())
            ->method('addRole')
            ->with('ROLE_ADMIN');

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->user);

        $this->task->run($this->output);
    }
}

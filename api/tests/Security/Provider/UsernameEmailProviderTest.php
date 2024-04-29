<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Security\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Dungap\Contracts\User\UserInterface;
use Dungap\Security\Provider\UsernameEmailProvider;
use Dungap\User\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class UsernameEmailProviderTest extends TestCase
{
    private MockObject|ManagerRegistry $managerRegistry;
    private MockObject|UserRepository $userRepository;
    private MockObject|UserInterface $user;
    private MockObject|ObjectManager $om;
    private UsernameEmailProvider $provider;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->om = $this->createMock(ObjectManager::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->provider = new UsernameEmailProvider($this->managerRegistry, $this->userRepository);
    }

    public function testRefreshUser(): void
    {
        $this->managerRegistry->expects($this->once())
            ->method('getManagerForClass')
            ->with(UserInterface::class)
            ->willReturn($this->om);

        $this->om->expects($this->once())
            ->method('refresh')
            ->with($this->user);

        $this->provider->refreshUser($this->user);
    }

    public function testUpgradePassword(): void
    {
        $this->user->expects($this->once())
            ->method('setPassword')
            ->with($password = 'hashed-password');

        $this->provider->upgradePassword($this->user, 'hashed-password');
    }

    public function testLoadUserByIdentifier(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByUsernameOrEmail')
            ->with($usernameOrEmail = 'username-or-email')
            ->willReturn($this->user);

        $this->provider->loadUserByIdentifier($usernameOrEmail);
    }

    public function testSupportsClass(): void
    {
        $provider = $this->provider;
        $symfonyUser = $this->createMock(SymfonyUserInterface::class);
        $user = $this->createMock(UserInterface::class);

        $this->assertTrue($provider->supportsClass(get_class($user)));
        $this->assertFalse($provider->supportsClass(get_class($symfonyUser)));
    }
}

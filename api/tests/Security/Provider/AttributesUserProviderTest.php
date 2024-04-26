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
use Dungap\Contracts\User\UserRepositoryInterface;
use Dungap\Contracts\UserInterface;
use Dungap\Security\Provider\AttributesUserProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class AttributesUserProviderTest extends TestCase
{
    private MockObject|ManagerRegistry $managerRegistry;
    private MockObject|UserRepositoryInterface $userRepository;
    private MockObject|ObjectManager $objectManager;
    private MockObject|UserInterface $user;
    private AttributesUserProvider $provider;

    public function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->provider = new AttributesUserProvider($this->managerRegistry, $this->userRepository);

        $this->managerRegistry->method('getManagerForClass')
            ->with(UserInterface::class)
            ->willReturn($this->objectManager);
    }

    public function testRefreshUser(): void
    {
        $this->objectManager->expects($this->once())
            ->method('refresh')
            ->with($this->user);

        $this->assertSame($this->user, $this->provider->refreshUser($this->user));
    }

    public function testLoadUserByIdentifier(): void
    {
        $attributes = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'groups' => [
                'Admin',
            ],
        ];

        $this->userRepository->expects($this->atLeastOnce())
            ->method('findByEmail')
            ->with($attributes['email'])
            ->willReturn($this->user);

        $this->user->expects($this->once())
            ->method('setEmail')
            ->with($attributes['email'])
            ->willReturnSelf();

        $this->user->expects($this->once())
            ->method('setName')
            ->with($attributes['name'])
            ->willReturnSelf();

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->user);

        $user = $this->provider->loadUserByIdentifier($attributes['email'], $attributes);
        $this->assertSame($this->user, $user);

        $this->expectException(UnsupportedUserException::class);
        unset($attributes['name']);
        $this->provider->loadUserByIdentifier($attributes['email'], $attributes);
    }

    public function testSupportsClass(): void
    {
        $user = $this->createMock(UserInterface::class);
        $symfonyUser = $this->createMock(SymfonyUserInterface::class);
        $provider = $this->provider;

        $this->assertTrue($provider->supportsClass(get_class($user)));
        $this->assertFalse($provider->supportsClass(get_class($symfonyUser)));
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Security\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\User\UserInterface;
use Dungap\Contracts\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<UserInterface>
 */
class UsernameEmailProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private ManagerRegistry $managerRegistry;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        ManagerRegistry $managerRegistry,
        UserRepositoryInterface $userRepository
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        assert($user instanceof UserInterface);
        $user->setPassword($newHashedPassword);
    }

    public function refreshUser(SymfonyUserInterface $user): UserInterface
    {
        assert($user instanceof UserInterface);

        $om = $this->managerRegistry->getManagerForClass(UserInterface::class);
        $om->refresh($user);

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        $implements = class_implements($class);

        return in_array(UserInterface::class, $implements);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $repository = $this->userRepository;

        return $repository->findByUsernameOrEmail($identifier) ?? $repository->create();
    }
}

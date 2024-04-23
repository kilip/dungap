<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dungap\Security\Core;

use Doctrine\Persistence\ManagerRegistry;
use Dungap\User\Entity\User;
use Dungap\User\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @implements AttributesBasedUserProviderInterface<UserInterface|User>
 */
final readonly class UserProvider implements AttributesBasedUserProviderInterface
{
    public function __construct(private ManagerRegistry $registry, private UserRepository $repository)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $manager = $this->registry->getManagerForClass($user::class);
        if (!$manager) {
            throw new UnsupportedUserException(sprintf('User class "%s" not supported.', $user::class));
        }

        $manager->refresh($user);

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    /**
     * Create or update User on login.
     *
     * @param array<string,mixed> $attributes
     */
    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface
    {
        $user = $this->repository->findOneBy(['email' => $identifier]) ?: new User();

        if (!isset($attributes['name'])) {
            throw new UnsupportedUserException('Property "name" is missing in token attributes.');
        }

        foreach ($attributes['groups'] as $group) {
            if ('admin' === strtolower($group)) {
                $user->addRole('ROLE_ADMIN');
            }
        }

        $user->setEmail($identifier)
            ->setName($attributes['name'])
        ;

        $this->repository->save($user);

        return $user;
    }
}

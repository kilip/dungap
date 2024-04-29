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

namespace Dungap\Security\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\User\UserInterface;
use Dungap\Contracts\User\UserRepositoryInterface;
use Dungap\User\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * @implements AttributesBasedUserProviderInterface<\Dungap\Contracts\User\UserInterface>
 */
final readonly class AttributesUserProvider implements AttributesBasedUserProviderInterface
{
    public function __construct(
        private ManagerRegistry $registry,
        private UserRepositoryInterface $repository
    ) {
    }

    public function refreshUser(SymfonyUserInterface $user): SymfonyUserInterface
    {
        assert($user instanceof UserInterface);
        $manager = $this->registry->getManagerForClass(UserInterface::class);

        $manager->refresh($user);

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        $implements = class_implements($class);

        return in_array(UserInterface::class, $implements);
    }

    /**
     * Create or update User on login.
     *
     * @param array<string,mixed> $attributes
     */
    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface
    {
        $user = $this->repository->findByEmail($identifier) ?: new User();

        if (!isset($attributes['name'])) {
            throw new UnsupportedUserException('Property "name" is missing in token attributes.');
        }

        foreach ($attributes['groups'] as $group) {
            if (in_array(strtolower($group), ['admin', 'dungap admins'])) {
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

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Security\Task;

use Dungap\Contracts\Task\InstallInterface;
use Dungap\Contracts\User\UserRepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SecurityInstallTask implements InstallInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        #[Autowire('%env(SECURITY_DEFAULT_ADMIN_USERNAME)%')]
        private string $defaultAdminUsername,
        #[Autowire('%env(SECURITY_DEFAULT_ADMIN_EMAIL)%')]
        private string $defaultAdminEmail,

        #[Autowire('%env(SECURITY_DEFAULT_ADMIN_PASSWORD)%')]
        private string $defaultAdminPassword,
    ) {
    }

    public function run(OutputInterface $output): void
    {
        $repository = $this->userRepository;
        $user = $repository->findByEmail($this->defaultAdminEmail);

        if (is_null($user)) {
            $user = $repository->create();
            $user->setEmail($this->defaultAdminEmail);
            $user->setPassword($this->defaultAdminPassword);
            $user->setUsername($this->defaultAdminUsername);
            $user->setName('Admin');

            $user->addRole('ROLE_ADMIN');

            $repository->save($user);
        }
    }
}

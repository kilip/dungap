<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dungap\User\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @codeCoverageIgnore
 */
class DungapFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%env(DUNGAP_DEFAULT_ADMIN_EMAIL)%')]
        private string $adminEmail,
        #[Autowire('%env(DUNGAP_DEFAULT_ADMIN_PASSWORD)%')]
        private string $adminPassword,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail($this->adminEmail);
        $user->setPassword($this->adminPassword);
        $user->addRole('ROLE_ADMIN');
        $manager->persist($user);

        $manager->flush();
    }
}

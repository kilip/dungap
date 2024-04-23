<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Setting\SettingInterface;
use Dungap\Contracts\Setting\SettingRepositoryInterface;
use Dungap\Setting\Entity\Setting;

/**
 * @extends ServiceEntityRepository<Setting>
 */
class SettingRepository extends ServiceEntityRepository implements SettingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function findByKey(string $key): ?SettingInterface
    {
        return $this->findOneBy(['key' => $key]);
    }

    public function store(SettingInterface $setting): void
    {
        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush();
    }
}

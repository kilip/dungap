<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Feature\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Feature\FeatureInterface;
use Dungap\Contracts\Feature\FeatureRepositoryInterface;

/**
 * @extends ServiceEntityRepository<FeatureInterface>
 */
class FeatureRepository extends ServiceEntityRepository implements FeatureRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatureInterface::class);
    }

    public function findByDevice(string $deviceId, string $feature): ?FeatureInterface
    {
        return $this->findOneBy(['deviceId' => $deviceId, 'feature' => $feature]);
    }
}

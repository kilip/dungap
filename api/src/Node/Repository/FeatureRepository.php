<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Core\Entity\RepositoryConcern;
use Dungap\Node\Entity\Feature;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Feature>
 */
class FeatureRepository extends ServiceEntityRepository implements FeatureRepositoryInterface
{
    /**
     * @use RepositoryConcern<FeatureInterface>
     */
    use RepositoryConcern;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feature::class);
    }

    public function findByFeature(Uuid $deviceId, string $feature): ?FeatureInterface
    {
        return $this->findOneBy([
            'node' => $deviceId,
            'name' => $feature,
        ]);
    }
}

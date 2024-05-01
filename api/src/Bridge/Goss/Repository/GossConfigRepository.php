<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Bridge\Goss\Contracts\GossConfigRepositoryInterface;
use Dungap\Bridge\Goss\Entity\GossConfig;
use Dungap\Contracts\Service\ServiceInterface;

/**
 * @extends ServiceEntityRepository<GossConfig>
 */
class GossConfigRepository extends ServiceEntityRepository implements GossConfigRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GossConfig::class);
    }

    public function findByService(ServiceInterface $service): ?GossConfigInterface
    {
        return $this->findOneBy([
            'service' => $service,
        ]);
    }

    public function create(): GossConfigInterface
    {
        return new GossConfig();
    }

    public function register(GossConfigInterface $config): void
    {
        $existing = $this->findOneBy([
            'service' => $config->getService(),
        ]);
        if (is_null($existing)) {
            $this->store($config);
        }
    }

    public function store(GossConfigInterface $config): void
    {
        $this->getEntityManager()->persist($config);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($config);
    }
}

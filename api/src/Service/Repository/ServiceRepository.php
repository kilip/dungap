<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Service\Entity\Service;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository implements ServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function create(): ServiceInterface
    {
        return new Service();
    }

    public function findByPort(?Uuid $deviceId, int $port): ?ServiceInterface
    {
        return $this->findOneBy([
            'device' => $deviceId,
            'port' => $port,
        ]);
    }

    public function register(ServiceInterface $service): void
    {
        $existing = $this->findOneBy([
            'device' => $service->getDevice()->getId(),
            'port' => $service->getPort(),
        ]);

        if (is_null($existing)) {
            $this->store($service);
        }
    }

    public function store(ServiceInterface $service): void
    {
        $this->getEntityManager()->persist($service);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($service);
    }
}

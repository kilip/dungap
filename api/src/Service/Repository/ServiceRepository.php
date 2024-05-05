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
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Core\Entity\RepositoryConcern;
use Dungap\Service\Entity\Service;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository implements ServiceRepositoryInterface
{
    /**
     * @use RepositoryConcern<ServiceInterface>
     */
    use RepositoryConcern;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findByNodePort(NodeInterface $node, int $port): ?ServiceInterface
    {
        return $this->findOneBy([
            'node' => $node,
            'port' => $port,
        ]);
    }
}

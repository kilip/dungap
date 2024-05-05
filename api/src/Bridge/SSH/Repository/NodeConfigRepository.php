<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Bridge\SSH\Contracts\NodeConfigInterface;
use Dungap\Bridge\SSH\Contracts\NodeConfigRepositoryInterface;
use Dungap\Bridge\SSH\Entity\NodeConfig;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Core\Entity\RepositoryConcern;

/**
 * @extends ServiceEntityRepository<NodeConfig>
 */
class NodeConfigRepository extends ServiceEntityRepository implements NodeConfigRepositoryInterface
{
    /**
     * @use RepositoryConcern<NodeConfigInterface>
     */
    use RepositoryConcern;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeConfig::class);
    }

    public function findByNode(NodeInterface $node): ?NodeConfigInterface
    {
        return $this->findOneBy([
            'node' => $node,
        ]);
    }
}

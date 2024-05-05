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
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Core\Entity\RepositoryConcern;
use Dungap\Node\Entity\Node;

/**
 * @extends ServiceEntityRepository<Node>
 */
class NodeRepository extends ServiceEntityRepository implements NodeRepositoryInterface
{
    /**
     * @use RepositoryConcern<NodeInterface>
     */
    use RepositoryConcern;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }

    public function findByName(string $name): ?NodeInterface
    {
        return $this->findOneBy(['name' => $name]);
    }
}

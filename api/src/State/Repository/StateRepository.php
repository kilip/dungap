<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\Core\Entity\RepositoryConcern;
use Dungap\State\Entity\State;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<State>
 */
class StateRepository extends ServiceEntityRepository implements StateRepositoryInterface
{
    use RepositoryConcern;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    public function getLastState(Uuid $entityId, string $name): ?StateInterface
    {
        return $this->findOneBy([
            'entityId' => $entityId,
            'name' => $name,
        ]);
    }
}

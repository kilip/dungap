<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dungap\Contracts\Device\CategoryInterface;
use Dungap\Contracts\Device\CategoryRepositoryInterface;
use Dungap\Device\Entity\Category;

/**
 * @extends ServiceEntityRepository<Category    >
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Category::class);
    }

    public function create(): CategoryInterface
    {
        return new Category();
    }

    public function findOrCreate(string $name): CategoryInterface
    {
        $category = $this->findOneBy(['name' => $name]);
        if (is_null($category)) {
            $category = $this->create();
            $category->setName($name);
            $this->store($category);
        }

        return $category;
    }

    public function store(CategoryInterface $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($category);
    }
}

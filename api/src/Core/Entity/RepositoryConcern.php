<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Core\Entity;

/**
 * @template T of object
 */
trait RepositoryConcern
{
    /**
     * @return T
     */
    public function create()
    {
        $class = $this->getClassName();

        return new $class();
    }

    /**
     * @param T $object
     */
    public function save($object): void
    {
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($object);
    }
}

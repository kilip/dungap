<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Core;

/**
 * @template T of object
 */
interface RepositoryInterface
{
    /**
     * @return T
     */
    public function create();

    /**
     * @param T $object
     */
    public function save($object): void;
}

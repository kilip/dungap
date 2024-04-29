<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Device;

interface CategoryRepositoryInterface
{
    public function create(): CategoryInterface;

    public function findOrCreate(string $name): CategoryInterface;

    public function store(CategoryInterface $category): void;
}

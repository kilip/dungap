<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Node;

use Dungap\Contracts\Core\RepositoryInterface;

/**
 * @extends RepositoryInterface<NodeInterface>
 */
interface NodeRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name): ?NodeInterface;

    /**
     * @return array<NodeInterface>
     */
    public function findAll(): array;
}

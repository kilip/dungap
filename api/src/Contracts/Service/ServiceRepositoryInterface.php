<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Service;

use Dungap\Contracts\Core\RepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;

/**
 * @extends RepositoryInterface<ServiceInterface>
 */
interface ServiceRepositoryInterface extends RepositoryInterface
{
    public function findByNodePort(NodeInterface $node, int $port): ?ServiceInterface;

    /**
     * @return array<ServiceInterface>
     */
    public function findAll(): array;
}

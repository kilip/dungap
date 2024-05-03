<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\State;

use Dungap\Contracts\Core\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends RepositoryInterface<StateInterface>
 */
interface StateRepositoryInterface extends RepositoryInterface
{
    public function getLastState(Uuid $entityId, string $name): ?StateInterface;
}

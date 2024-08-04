<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Concern;

use Dungap\Attribute\Repository\MetaRepository;
use Dungap\Contracts\Attribute\MetaRepositoryInterface;

trait AttributeConcern
{
    public function getMetaRepository(): MetaRepositoryInterface
    {
        return $this->getService(MetaRepository::class);
    }
}

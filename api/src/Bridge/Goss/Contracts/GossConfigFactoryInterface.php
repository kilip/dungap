<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Contracts;

interface GossConfigFactoryInterface
{
    /**
     * @param array<int, GossConfigInterface> $configs
     */
    public function create(array $configs): GossConfigFileInterface;
}

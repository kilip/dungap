<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Config;

class Config
{
    /**
     * @var iterable<Addr>
     */
    public iterable $addr;

    public function addAddr(string $id, Addr $addr): void
    {
        $this->addr[$id] = $addr;
    }
}

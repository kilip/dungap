<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Setting\ConfigInterface;

interface ConfigInterface
{
    /**
     * @return array<int, mixed>
     */
    public function getScanner(): array;
}

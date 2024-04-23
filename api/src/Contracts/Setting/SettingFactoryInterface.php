<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Setting;

interface SettingFactoryInterface
{
    /**
     * @template  T of object
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function get(string $key, string $className, bool $create = true): ?object;
}

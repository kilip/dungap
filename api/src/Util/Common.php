<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Util;

class Common
{
    public static function fileWrite(string $target, string $contents): void
    {
        if (!is_dir($dir = dirname($target))) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($target, $contents);
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('ensureFileDirExists')) {
    /**
     * @codeCoverageIgnore
     */
    function ensureFileDirExists(string $filename): void
    {
        if (!is_dir($dir = dirname($filename))) {
            mkdir($dir, 0777, true);
        }
    }
}

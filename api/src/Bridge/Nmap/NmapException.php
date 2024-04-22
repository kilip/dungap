<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Nmap;

class NmapException extends \Exception
{
    public static function resultFileNotExists(string $filename): self
    {
        return new self(sprintf(
            'Can not parse result file "%s". Result file not exists',
            $filename
        ));
    }
}

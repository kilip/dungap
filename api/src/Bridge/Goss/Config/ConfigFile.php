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

use Dungap\Bridge\Goss\Contracts\GossConfigFileInterface;
use Dungap\Util\Common;

class ConfigFile implements GossConfigFileInterface
{
    public function __construct(
        private string $filename,
    ) {
    }

    public function getFileName(): string
    {
        return $this->filename;
    }

    public function write(string $contents): void
    {
        Common::fileWrite($this->filename, $contents);
    }
}

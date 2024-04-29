<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class Loader extends FileLoader
{
    public function load(mixed $resource, string $type = null): mixed
    {
        return Yaml::parse(file_get_contents($resource));
    }

    public function supports(mixed $resource, string $type = null): bool
    {
        return is_string($resource);
    }
}

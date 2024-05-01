<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Util\Serializer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class HypenNameConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return strtolower(preg_replace('/[A-Z]/', '-\\0', lcfirst($propertyName)));
    }

    public function denormalize(string $propertyName): string
    {
        $camelCasedName = preg_replace_callback('/(^|-|\.)+(.)/', fn ($match) => ('.' === $match[1] ? '-' : '').strtoupper($match[2]), $propertyName);

        return lcfirst($camelCasedName);
    }
}

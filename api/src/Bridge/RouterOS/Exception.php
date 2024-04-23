<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS;

class Exception extends \Exception
{
    public static function propertyNotFound(object $object, string $propName): self
    {
        return new self(sprintf('Property "%s" not exists in class "%s".',
            $propName,
            get_class($object)
        ));
    }
}

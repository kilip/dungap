<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State;

class StateException extends \Exception
{
    public static function updateStateFailed(string $name, string $state, string $error): self
    {
        return new self(sprintf(
            'Failed to change state "%s" to "%s"; Error: %s',
            $name,
            $state,
            $error
        ));
    }
}

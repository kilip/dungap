<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH;

class SecureException extends \Exception
{
    public static function loginFailed(string $host, int $port, string $username): self
    {
        return new self(sprintf(
            'Failed to login to %s:%s, with username %s',
            $host,
            $port,
            $username
        ));
    }
}

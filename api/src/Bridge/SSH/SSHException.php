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

class SSHException extends \Exception
{
    public static function failToLogin(Configuration $config): self
    {
        return new self(sprintf(
            'Failed to ssh login to host "%s" with username "%s".',
            $config->host,
            $config->username,
        ));
    }
}

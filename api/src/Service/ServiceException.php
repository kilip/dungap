<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service;

class ServiceException extends \Exception
{
    public static function serviceScanFailed(
        string $nodeName,
        int $port,
        string $error
    ): self {
        return new self(sprintf(
            'Failed to scan service for node "%s" port "%s". Error: %s',
            $nodeName,
            $port,
            $error
        ));
    }
}

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

use Dungap\Contracts\Service\ServiceInterface;

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

    public static function failedToValidateService(ServiceInterface $service, \Exception $e): self
    {
        return new self(sprintf(
            'Failed to validate service "%s":%s. Error: %s',
            $service->getNode()->getName(),
            $service->getPort(),
            $e->getMessage(),
        ));
    }

    public static function failedToDispatchValidatedEvent(ServiceInterface $service, \Exception $e): self
    {
        return new self(sprintf(
            'Failed to dispatch validate event on service "%s":%s. Error: %s',
            $service->getNode()->getName(),
            $service->getPort(),
            $e->getMessage(),
        ));
    }
}

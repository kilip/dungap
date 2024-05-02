<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Report;

use Dungap\Bridge\Goss\Util;
use Dungap\Contracts\Service\ValidatorResultInterface;

class Result implements ValidatorResultInterface
{
    private string $resourceId;
    private bool $successful;
    private string $serviceId;
    private string $tcpId;

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function setResourceId(string $resourceId): void
    {
        $this->resourceId = $resourceId;
        $this->tcpId = Util::getTcpID($resourceId);
        $serviceId = Util::getServiceId($resourceId) ?? $this->tcpId;
        $this->serviceId = $serviceId;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function setSuccessful(bool $successful): void
    {
        $this->successful = $successful;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getTcpId(): string
    {
        return $this->tcpId;
    }
}

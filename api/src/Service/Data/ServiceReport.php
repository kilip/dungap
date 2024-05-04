<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Data;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceReportInterface;

final readonly class ServiceReport implements ServiceReportInterface
{
    public function __construct(
        private NodeInterface $node,
        private int $port,
        private bool $successful,
        private int $timeout,
        private ?string $error = null,
        private ?int $errorCode = null,
        private ?float $latency = null,
    ) {
    }

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getLatency(): ?float
    {
        return $this->latency;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}

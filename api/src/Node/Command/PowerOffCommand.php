<?php

namespace Dungap\Node\Command;

final readonly class PowerOffCommand
{
    public function __construct(
        private string $nodeId
    )
    {
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }
}
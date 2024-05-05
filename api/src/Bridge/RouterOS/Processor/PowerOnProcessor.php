<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Processor;

use Dungap\Bridge\RouterOS\Contracts\WakeOnLanRequestInterface;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\PowerOnProcessorInterface;
use Dungap\Dungap;

final readonly class PowerOnProcessor implements PowerOnProcessorInterface
{
    public function __construct(
        private WakeOnLanRequestInterface $wolRequest
    ) {
    }

    public function getDriverName(): string
    {
        return Dungap::RouterOsDriver;
    }

    public function process(FeatureInterface $feature): void
    {
        $mac = $feature->getNode()->getMac();
        $this->wolRequest->execute($mac);
    }
}

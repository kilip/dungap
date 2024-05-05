<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node;

use Dungap\Contracts\Node\FeatureInterface;

class NodeException extends \Exception
{
    public static function powerOnProcessorInvalid(FeatureInterface $feature): self
    {
        return new self(sprintf(
            'Power on processor for node "%s" with driver "%s" not found.',
            $feature->getNode()->getName(),
            $feature->getDriver()
        ));
    }
}

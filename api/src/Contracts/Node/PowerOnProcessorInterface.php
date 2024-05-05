<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Node;

use Dungap\Dungap;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(Dungap::PowerOnProcessorTag)]
interface PowerOnProcessorInterface
{
    public function getDriverName(): string;

    public function process(FeatureInterface $feature): void;
}

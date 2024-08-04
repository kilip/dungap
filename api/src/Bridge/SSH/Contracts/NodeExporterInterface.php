<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Contracts;

use Dungap\Bridge\SSH\SSH;
use Dungap\Contracts\Node\NodeInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(SSH::ExporterServiceTag)]
interface NodeExporterInterface
{
    public function process(NodeInterface $node, SshInterface $ssh): void;
}

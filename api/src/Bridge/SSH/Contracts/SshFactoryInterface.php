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

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\SSH\SshFactoryInterface as BaseSshFactoryInterface;

interface SshFactoryInterface extends BaseSshFactoryInterface
{
    public function createSshClient(NodeInterface $node): SshInterface;
}

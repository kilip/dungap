<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Processor;

use Dungap\Contracts\Node\PowerOffProcessorInterface;

final readonly class PowerOffProcessor implements PowerOffProcessorInterface
{
    use SshProcessorConcern;

    protected function getCommand(): string
    {
        return 'sudo poweroff';
    }
}

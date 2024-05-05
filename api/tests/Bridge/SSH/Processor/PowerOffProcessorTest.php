<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Processor;

use Dungap\Bridge\SSH\Processor\PowerOffProcessor;
use PHPUnit\Framework\TestCase;

class PowerOffProcessorTest extends TestCase
{
    use TestSshProcessorConcern;

    protected function getProcessorClassName(): string
    {
        return PowerOffProcessor::class;
    }

    protected function getSshCommand(): string
    {
        return 'sudo poweroff';
    }
}

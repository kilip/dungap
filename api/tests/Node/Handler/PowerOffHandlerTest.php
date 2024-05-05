<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Handler;

use Dungap\Dungap;
use Dungap\Node\Command\PowerOffCommand;
use Dungap\Node\Handler\PowerOffHandler;

class PowerOffHandlerTest extends FeatureHandlerTestCase
{
    protected string $handlerClass = PowerOffHandler::class;
    protected string $commandClass = PowerOffCommand::class;
    protected string $featureName = Dungap::PowerOffFeature;
}

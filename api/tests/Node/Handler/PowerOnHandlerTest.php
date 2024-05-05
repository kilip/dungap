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
use Dungap\Node\Command\PowerOnCommand;
use Dungap\Node\Handler\PowerOnHandler;

/**
 * @covers \Dungap\Node\Handler\PowerOnHandler
 * @covers \Dungap\Node\Handler\AbstractFeatureHandler
 */
class PowerOnHandlerTest extends FeatureHandlerTestCase
{
    protected string $handlerClass = PowerOnHandler::class;
    protected string $commandClass = PowerOnCommand::class;
    protected string $featureName = Dungap::PowerOnFeature;
}

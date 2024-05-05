<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\State\Controller;

use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\State\Controller\LatestAction;
use PHPUnit\Framework\TestCase;

class LatestActionTest extends TestCase
{
    public function testInvoke(): void
    {
        $states = $this->createMock(StateRepositoryInterface::class);
        $action = new LatestAction($states);

        $states->expects($this->once())
            ->method('findLatest')
            ->with('node.online.zeus');

        $action('node.online.zeus');
    }
}

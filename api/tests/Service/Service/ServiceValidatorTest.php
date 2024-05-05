<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Service;

use Dungap\Node\Entity\Node;
use Dungap\Service\Service\ServiceValidator;
use PHPUnit\Framework\TestCase;

class ServiceValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $node = new Node();
        $validator = new ServiceValidator();

        $node->setName('github');
        $node->setHostname('github.com');
        $report = $validator->validate($node, 22, 1000);

        $this->assertTrue($report->isSuccessful());
        $this->assertNotNull($report->getLatency());
    }

    public function testValidateError(): void
    {
        $node = new Node();
        $validator = new ServiceValidator();

        $node->setName('foo');
        $node->setIp('192.168.254.254');
        $report = $validator->validate($node, 80, 100);

        $this->assertFalse($report->isSuccessful());
        $this->assertNull($report->getLatency());
    }
}

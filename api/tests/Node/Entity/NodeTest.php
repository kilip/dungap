<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Entity;

use Dungap\Node\Entity\Attribute;
use Dungap\Tests\Concern\ContainerConcern;
use Dungap\Tests\Concern\NodeConcern;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NodeTest extends KernelTestCase
{
    use ContainerConcern;
    use NodeConcern;

    public function testGetAttributes()
    {
        $node = $this->getNodeRepository()->findByName('test');

        if (is_null($node)) {
            $node = $this->getNodeRepository()->create();
        }

        $attribute = new Attribute();
        $attribute->setName('uptime');
        $attribute->setValue(new \DateTimeImmutable());
        $attribute->setType('datetime');

        $node->setName('test');
        $node->addAttribute($attribute);

        $this->getNodeRepository()->save($node);

        $this->assertNotNull($node->getId());
        $this->assertTrue($node->hasAttribute($attribute->getName()));
    }
}

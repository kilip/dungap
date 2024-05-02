<?php

namespace Dungap\Tests\Bridge\Goss;

use Dungap\Bridge\Goss\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{

    public function testGetIds()
    {
        $id = '018f3749-f8e6-7db3-88ec-0dc4728c569d';
        $tcp = "tcp://192.168.10.1:22";

        $resourceId = "{$id}: {$tcp}";

        $this->assertSame(
            $id,
            Util::getServiceId($resourceId)
        );
        $this->assertNull(Util::getServiceId($tcp));

        $this->assertSame(
            $tcp,
            Util::getTcpID($resourceId)
        );
    }
}

<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Setting;

use Dungap\Setting\SettingFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class SettingFactoryTest extends KernelTestCase
{
    // use ResetDatabase;

    public function setUp(): void
    {
        static::bootKernel();
    }

    public function testGet(): void
    {
        /** @var SettingFactory $factory */
        $factory = $this->getContainer()->get(SettingFactory::class);
        $ob = $factory->get('test_setting.global', TestSetting::class);

        $this->assertIsObject($ob);
        $this->assertInstanceOf(TestSetting::class, $ob);

        $ob->foo = 'Hello World';
        $factory->save('test_setting.global', $ob);

        $this->assertSame('Hello World', $ob->foo);
    }
}

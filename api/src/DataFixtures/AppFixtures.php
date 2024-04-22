<?php

namespace Dungap\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dungap\Tests\Story\DefaultDeviceStory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        echo "hello world";
        DefaultDeviceStory::load();
        $manager->flush();
    }
}

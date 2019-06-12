<?php

namespace App\DataFixtures;

use App\Entity\Area;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AreaFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(30, 'main_area', function($i) use ($manager) {
            $area = new Area();
            $area->setName($this->faker->city);
            $area->addEntity($this->getRandomReference('main_entity'));

            return $area;
        });
        $manager->flush();
    }
    public function getDependencies() {
        return [
            EntityFixtures::class,
        ];
    }
}

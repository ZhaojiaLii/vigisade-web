<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DirectionFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(30, 'main_direction', function($i) use ($manager) {
            $direction = new Direction();
            $direction->setName($this->faker->country);
            $direction->setEtat(true);
            $direction->addArea($this->getRandomReference('main_area'));

            return $direction;
        });
        $manager->flush();
    }
    public function getDependencies() {
        return [
            AreaFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DirectionFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $arrayDirections = [
            ["name" => "COTTEL Reseaux"],
            ["name" => "ETE Reseaux"],
            ["name" => "France"],
            ["name" => "FILIALES"],
            ["name" => "SST"],
            ["name" => "Activite nucleaire"],
        ];

        foreach ($arrayDirections as $arrayDirection){

            $direction = new Direction();
            $direction->setName($arrayDirection['name']);
            $direction->setEtat(true);

            $manager->persist($direction);
        }
        $manager->flush();
    }
}

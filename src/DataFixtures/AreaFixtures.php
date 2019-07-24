<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Direction;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AreaFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $arrayAreas = [
            ["direction_name" => "France", "name" => "Ouest"],
            ["direction_name" => "France", "name" => "Normandie"],
            ["direction_name" => "France", "name" => "Sud-Ouest"],
            ["direction_name" => "France", "name" => "ile-de-France"],
            ["direction_name" => "France", "name" => "Hauts-de-France"],
            ["direction_name" => "France", "name" => "EST"],
            ["direction_name" => "France", "name" => "Centre est"],
            ["direction_name" => "France", "name" => "SADE Services"],
            ["direction_name" => "France", "name" => "Sud"],
            ["direction_name" => "France", "name" => "STS"],
            ["direction_name" => "FILIALES", "name" => "FILIALES"],
            ["direction_name" => "COTTEL Reseaux", "name" => "AUTRES"],
            ["direction_name" => "COTTEL Reseaux", "name" => "CA"],
            ["direction_name" => "COTTEL Reseaux", "name" => "CDC"],
            ["direction_name" => "COTTEL Reseaux", "name" => "CDP/RA"],
            ["direction_name" => "COTTEL Reseaux", "name" => "CDT"],
            ["direction_name" => "COTTEL Reseaux", "name" => "CHSCT"],
            ["direction_name" => "COTTEL Reseaux", "name" => "DIRECTION"],
            ["direction_name" => "COTTEL Reseaux", "name" => "SQE"],
            ["direction_name" => "ETE Reseaux", "name" => "AUTRES"],
            ["direction_name" => "ETE Reseaux", "name" => "CA"],
            ["direction_name" => "ETE Reseaux", "name" => "CDC"],
            ["direction_name" => "ETE Reseaux", "name" => "CDP/RA"],
            ["direction_name" => "ETE Reseaux", "name" => "CDT"],
            ["direction_name" => "ETE Reseaux", "name" => "CHSCT"],
            ["direction_name" => "ETE Reseaux", "name" => "DIRECTION"],
            ["direction_name" => "ETE Reseaux", "name" => "SQE"],
            ["direction_name" => "SST", "name" => "AUTRES"],
            ["direction_name" => "SST", "name" => "CA"],
            ["direction_name" => "SST", "name" => "CDC"],
            ["direction_name" => "SST", "name" => "CDP/RA"],
            ["direction_name" => "SST", "name" => "CDT"],
            ["direction_name" => "SST", "name" => "CHSCT"],
            ["direction_name" => "SST", "name" => "DIRECTION"],
            ["direction_name" => "SST", "name" => "SQE"],
            ["direction_name" => "Activite nucleaire", "name" => "Normandie"],
            ["direction_name" => "Activite nucleaire", "name" => "Sud-Ouest"],
            ["direction_name" => "Activite nucleaire", "name" => "ile-de-France"],
            ["direction_name" => "Activite nucleaire", "name" => "Hauts-de-France"],
            ["direction_name" => "Activite nucleaire", "name" => "EST"],
            ["direction_name" => "Activite nucleaire", "name" => "Centre est"]
        ];

        foreach ($arrayAreas as $arrayArea){
            $direction = $manager->getRepository(Direction::class)->findBy(['name' => $arrayArea['direction_name']]);
            $area = new Area();
            $area->setName($arrayArea['name']);
            $area->setEtat(true);
            $area->setDirection($direction[0]);

            $manager->persist($area);
        }
        $manager->flush();
    }
    public function getDependencies() {
        return [
            DirectionFixtures::class,
        ];
    }
}

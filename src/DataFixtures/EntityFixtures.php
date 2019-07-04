<?php

namespace App\DataFixtures;

use App\Entity\Entity;
use Doctrine\Common\Persistence\ObjectManager;

class EntityFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(30, 'main_entity', function($i) use ($manager) {
            $entity = new Entity();
            $entity->setName($this->faker->state);
            $entity->setEtat(true);
            return $entity;
        });
        $manager->flush();
    }

}

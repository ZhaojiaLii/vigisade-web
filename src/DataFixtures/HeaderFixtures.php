<?php

namespace App\DataFixtures;

use App\Entity\Header;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class HeaderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $header = new Header();
        $header->setImage('');
        $header->setLogoText('Ceci est un texte Header.');
        $header->setNews('On sait depuis longtemps que travailler avec du texte lisible e..');
        $header->setUpdatedAt(new \DateTime());

        $manager->persist($header);
        $manager->flush();
    }
}

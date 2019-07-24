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
        $header->setLogoText('Header - Page Accueil');
        $header->setNews('Dans le cadre de la gestion dynamique des réseaux d’assainissement de sa région, le SIVOM Région Mulhouse a confié à la SADE la mise en place d’ouvrages de régulation. Les travaux comprendront : cuvelage étanche, blindage, dévoiement gravitaire de réseaux, construction et pose à la grue d’ouvrages de régulation (GECITEC, filiale SADE) et de réseaux secs de liaison, travaux d’électromécanique (CIEMA, filiale SADE).');
        $header->setUpdatedAt(new \DateTime());

        $manager->persist($header);
        $manager->flush();
    }
}

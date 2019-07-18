<?php

namespace App\DataFixtures;

use App\Entity\BestPractice;
use App\Entity\BestPracticeTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeBestPratiqueFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $typeBestPratique1 = new BestPractice();
        $typeBestPratique1->setStatus('1');

        $typeBestPratique1TranslationEn = new BestPracticeTranslation();
        $typeBestPratique1TranslationEn->setLocale('en');
        $typeBestPratique1TranslationEn->setTranslatable($typeBestPratique1);
        $typeBestPratique1TranslationEn->setType('Type Best pratique 1 EN');

        $typeBestPratique1TranslationEs = new BestPracticeTranslation();
        $typeBestPratique1TranslationEs->setLocale('es');
        $typeBestPratique1TranslationEs->setTranslatable($typeBestPratique1);
        $typeBestPratique1TranslationEs->setType('Type Best pratique 1 ES');

        $typeBestPratique1TranslationFn = new BestPracticeTranslation();
        $typeBestPratique1TranslationFn->setLocale('fr');
        $typeBestPratique1TranslationFn->setTranslatable($typeBestPratique1);
        $typeBestPratique1TranslationFn->setType('Type Best pratique 1 FN');


        $typeBestPratique2 = new BestPractice();
        $typeBestPratique2->setStatus('1');

        $typeBestPratique2TranslationEn = new BestPracticeTranslation();
        $typeBestPratique2TranslationEn->setLocale('en');
        $typeBestPratique2TranslationEn->setTranslatable($typeBestPratique2);
        $typeBestPratique2TranslationEn->setType('Type Best pratique 2 EN');

        $typeBestPratique2TranslationEs = new BestPracticeTranslation();
        $typeBestPratique2TranslationEs->setLocale('es');
        $typeBestPratique2TranslationEs->setTranslatable($typeBestPratique2);
        $typeBestPratique2TranslationEs->setType('Type Best pratique 2 ES');

        $typeBestPratique2TranslationFn = new BestPracticeTranslation();
        $typeBestPratique2TranslationFn->setLocale('fr');
        $typeBestPratique2TranslationFn->setTranslatable($typeBestPratique2);
        $typeBestPratique2TranslationFn->setType('Type Best pratique 2 FN');

        $manager->persist($typeBestPratique1TranslationEn);
        $manager->persist($typeBestPratique1TranslationEs);
        $manager->persist($typeBestPratique1TranslationFn);

        $manager->persist($typeBestPratique2TranslationEn);
        $manager->persist($typeBestPratique2TranslationEs);
        $manager->persist($typeBestPratique2TranslationFn);

        $manager->persist($typeBestPratique1);
        $manager->persist($typeBestPratique2);
        $manager->flush();
    }
}

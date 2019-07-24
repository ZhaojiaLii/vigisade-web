<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use App\Entity\Survey;
use App\Entity\SurveyCategory;
use App\Entity\SurveyCategoryTranslation;
use App\Entity\SurveyQuestion;
use App\Entity\SurveyQuestionTranslation;
use App\Entity\SurveyTranslation;
use App\Repository\DirectionRepository;
use App\Repository\SurveyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class SurveyFixtures extends Fixture
{
    private $em;
    private $directionRepository;

    public function __construct(
        EntityManagerInterface $em,
        DirectionRepository $directionRepository
    )
    {
        $this->em = $em;
        $this->directionRepository = $em;
    }

    public function load(ObjectManager $manager)
    {
        //$direction
        $direction = new Direction();
        $direction->setName('France');
        $direction->setEtat(1);

        //$survey
        $survey = new Survey();
        $survey->setDirection($direction);
        $survey->setUpdatedAt(new \DateTime());
        $survey->setTeam(1);
        $survey->setCountTeam(5);

        //$surveyTranslationFr
        $surveyTranslationFr = new SurveyTranslation();
        $surveyTranslationFr->setTranslatable($survey);
        $surveyTranslationFr->setBestPracticeHelp('Avez-vous identifié  une pratique remarquable au cours de cette visite ?');
        $surveyTranslationFr->setBestPracticeLabel('Un ensemble de comportements qui font consensus et qui sont considérés comme indispensables par la plupart des professionnels du domaine');
        $surveyTranslationFr->setLocale('fr');
        $surveyTranslationFr->setTitle('VIGISADE FR');

        //$surveyTranslationEn
        $surveyTranslationEn = new SurveyTranslation();
        $surveyTranslationEn->setTranslatable($survey);
        $surveyTranslationEn->setBestPracticeHelp('Did you identify a remarkable practice during this visit?');
        $surveyTranslationEn->setBestPracticeLabel('A set of behaviours that are widely accepted and considered essential by most professionals in the field');
        $surveyTranslationEn->setLocale('en');
        $surveyTranslationEn->setTitle('VIGISADE EN');

        //$surveyTranslationEs
        $surveyTranslationEs = new SurveyTranslation();
        $surveyTranslationEs->setTranslatable($survey);
        $surveyTranslationEs->setBestPracticeHelp('¿Identificó una práctica notable durante esta visita?');
        $surveyTranslationEs->setBestPracticeLabel('Un conjunto de comportamientos que son ampliamente aceptados y considerados esenciales por la mayoría de los profesionales del sector');
        $surveyTranslationEs->setLocale('es');
        $surveyTranslationEs->setTitle('VIGISADE ES');

        /*
         * Category 1 Visite
         */

        //$surveyCategory 1
        $surveyCategory1 = new SurveyCategory();
        $surveyCategory1->setSurvey($survey);
        $surveyCategory1->setUpdatedAt(new \DateTime());
        $surveyCategory1->setCategoryOrder(1);

        //$surveyCategoryTranslationFr
        $surveyCategoryTranslationFr = new SurveyCategoryTranslation();
        $surveyCategoryTranslationFr->setTitle('Sécurité');
        $surveyCategoryTranslationFr->setLocale('fr');
        $surveyCategoryTranslationFr->setTranslatable($surveyCategory1);

        //$surveyCategoryTranslationEn
        $surveyCategoryTranslationEn = new SurveyCategoryTranslation();
        $surveyCategoryTranslationEn->setTitle('Securtiy');
        $surveyCategoryTranslationEn->setLocale('en');
        $surveyCategoryTranslationEn->setTranslatable($surveyCategory1);

        //$surveyCategoryTranslationEs
        $surveyCategoryTranslationEs = new SurveyCategoryTranslation();
        $surveyCategoryTranslationEs->setTitle('Seguridad y protección');
        $surveyCategoryTranslationEs->setLocale('es');
        $surveyCategoryTranslationEs->setTranslatable($surveyCategory1);

        //$surveyQuestion1
        $surveyQuestion1 = new SurveyQuestion();
        $surveyQuestion1->setCategory($surveyCategory1);
        $surveyQuestion1->setQuestionOrder(1);
        $surveyQuestion1->setQuestionType('General');

        //$surveyQuestion1TranslationFr
        $surveyQuestion1TranslationFr = new SurveyQuestionTranslation();
        $surveyQuestion1TranslationFr->setTranslatable($surveyQuestion1);
        $surveyQuestion1TranslationFr->setLocale('fr');
        $surveyQuestion1TranslationFr->setHelp('Equipements de Protection Collective (signalisation, blindage, garde-corps, échafaudage, dérivation pompage, ...');
        $surveyQuestion1TranslationFr->setLabel('E.P.C');

        //$surveyQuestion1TranslationEn
        $surveyQuestion1TranslationEn = new SurveyQuestionTranslation();
        $surveyQuestion1TranslationEn->setTranslatable($surveyQuestion1);
        $surveyQuestion1TranslationEn->setLocale('en');
        $surveyQuestion1TranslationEn->setHelp('Collective Protection Equipment (signalling, armouring, guardrails, scaffolding, pumping bypass, etc.)');
        $surveyQuestion1TranslationEn->setLabel('E.P.C');

        //$surveyQuestion1TranslationEs
        $surveyQuestion1TranslationEs = new SurveyQuestionTranslation();
        $surveyQuestion1TranslationEs->setTranslatable($surveyQuestion1);
        $surveyQuestion1TranslationEs->setLocale('es');
        $surveyQuestion1TranslationEs->setHelp('Equipos de protección colectiva (señalización, blindaje, barandillas, andamios, bypass de bombeo, etc.)');
        $surveyQuestion1TranslationEs->setLabel('E.P.C');

        //$surveyQuestion2
        $surveyQuestion2 = new SurveyQuestion();
        $surveyQuestion2->setCategory($surveyCategory1);
        $surveyQuestion2->setQuestionOrder(2);
        $surveyQuestion2->setQuestionType('General');

        //$surveyQuestion1TranslationFr
        $surveyQuestion2TranslationFr = new SurveyQuestionTranslation();
        $surveyQuestion2TranslationFr->setTranslatable($surveyQuestion2);
        $surveyQuestion2TranslationFr->setLocale('fr');
        $surveyQuestion2TranslationFr->setHelp('');
        $surveyQuestion2TranslationFr->setLabel('E.P.I');

        //$surveyQuestion1TranslationEn
        $surveyQuestion2TranslationEn = new SurveyQuestionTranslation();
        $surveyQuestion2TranslationEn->setTranslatable($surveyQuestion2);
        $surveyQuestion2TranslationEn->setLocale('en');
        $surveyQuestion2TranslationEn->setHelp('');
        $surveyQuestion2TranslationEn->setLabel('E.P.I');

        //$surveyQuestion1TranslationEs
        $surveyQuestion2TranslationEs = new SurveyQuestionTranslation();
        $surveyQuestion2TranslationEs->setTranslatable($surveyQuestion2);
        $surveyQuestion2TranslationEs->setLocale('es');
        $surveyQuestion2TranslationEs->setHelp('');
        $surveyQuestion2TranslationEs->setLabel('E.P.I');

        //$surveyQuestion3
        $surveyQuestion3 = new SurveyQuestion();
        $surveyQuestion3->setCategory($surveyCategory1);
        $surveyQuestion3->setQuestionOrder(3);
        $surveyQuestion3->setQuestionType('General');

        //$surveyQuestion3TranslationFr
        $surveyQuestion3TranslationFr = new SurveyQuestionTranslation();
        $surveyQuestion3TranslationFr->setTranslatable($surveyQuestion3);
        $surveyQuestion3TranslationFr->setLocale('fr');
        $surveyQuestion3TranslationFr->setHelp('');
        $surveyQuestion3TranslationFr->setLabel('Matérialisation du tracé au sol des réseaux concessionnaires');

        //$surveyQuestion3TranslationEn
        $surveyQuestion3TranslationEn = new SurveyQuestionTranslation();
        $surveyQuestion3TranslationEn->setTranslatable($surveyQuestion3);
        $surveyQuestion3TranslationEn->setLocale('en');
        $surveyQuestion3TranslationEn->setHelp('');
        $surveyQuestion3TranslationEn->setLabel('Materialization of the footprint of the concessionary networks');

        //$surveyQuestion3TranslationEs
        $surveyQuestion3TranslationEs = new SurveyQuestionTranslation();
        $surveyQuestion3TranslationEs->setTranslatable($surveyQuestion3);
        $surveyQuestion3TranslationEs->setLocale('es');
        $surveyQuestion3TranslationEs->setHelp('');
        $surveyQuestion3TranslationEs->setLabel('Materialización de la huella de las redes concesionarias');
        
        //$surveyQuestion4
        $surveyQuestion4 = new SurveyQuestion();
        $surveyQuestion4->setCategory($surveyCategory1);
        $surveyQuestion4->setQuestionOrder(4);
        $surveyQuestion4->setQuestionType('General');

        //$surveyQuestion4TranslationFr
        $surveyQuestion4TranslationFr = new SurveyQuestionTranslation();
        $surveyQuestion4TranslationFr->setTranslatable($surveyQuestion4);
        $surveyQuestion4TranslationFr->setLocale('fr');
        $surveyQuestion4TranslationFr->setHelp('');
        $surveyQuestion4TranslationFr->setLabel('Situation dangereuse identifiée');

        //$surveyQuestion4TranslationEn
        $surveyQuestion4TranslationEn = new SurveyQuestionTranslation();
        $surveyQuestion4TranslationEn->setTranslatable($surveyQuestion4);
        $surveyQuestion4TranslationEn->setLocale('en');
        $surveyQuestion4TranslationEn->setHelp('');
        $surveyQuestion4TranslationEn->setLabel('Hazardous situation identified');

        //$surveyQuestion4TranslationEs
        $surveyQuestion4TranslationEs = new SurveyQuestionTranslation();
        $surveyQuestion4TranslationEs->setTranslatable($surveyQuestion4);
        $surveyQuestion4TranslationEs->setLocale('es');
        $surveyQuestion4TranslationEs->setHelp('');
        $surveyQuestion4TranslationEs->setLabel('Situación peligrosa identificada');

        /*
         * Category 2 Security
         */

        //$surveyCategory 2
        $surveyCategory2 = new SurveyCategory();
        $surveyCategory2->setSurvey($survey);
        $surveyCategory2->setUpdatedAt(new \DateTime());
        $surveyCategory2->setCategoryOrder(2);

        //$surveyCategory2TranslationFr
        $surveyCategory2TranslationFr = new SurveyCategoryTranslation();
        $surveyCategory2TranslationFr->setTitle('Securite fr');
        $surveyCategory2TranslationFr->setLocale('fr');
        $surveyCategory2TranslationFr->setTranslatable($surveyCategory2);

        //$surveyCategory2TranslationEn
        $surveyCategory2TranslationEn = new SurveyCategoryTranslation();
        $surveyCategory2TranslationEn->setTitle('Security En');
        $surveyCategory2TranslationEn->setLocale('en');
        $surveyCategory2TranslationEn->setTranslatable($surveyCategory2);

        //$surveyCategory2TranslationEs
        $surveyCategory2TranslationEs = new SurveyCategoryTranslation();
        $surveyCategory2TranslationEs->setTitle('seguridad Es');
        $surveyCategory2TranslationEs->setLocale('es');
        $surveyCategory2TranslationEs->setTranslatable($surveyCategory2);


        //$surveyCategory2Question1
        $surveyCategory2Question1 = new SurveyQuestion();
        $surveyCategory2Question1->setCategory($surveyCategory2);
        $surveyCategory2Question1->setQuestionOrder(1);
        $surveyCategory2Question1->setQuestionType('Equipe');

        //$surveyCategory2Question1TranslationFr
        $surveyCategory2Question1TranslationFr = new SurveyQuestionTranslation();
        $surveyCategory2Question1TranslationFr->setTranslatable($surveyCategory2Question1);
        $surveyCategory2Question1TranslationFr->setLocale('fr');
        $surveyCategory2Question1TranslationFr->setHelp('help category Securite question 1 FR');
        $surveyCategory2Question1TranslationFr->setLabel('label category Securite question 1 FR');

        //$surveyCategory2Question1TranslationEn
        $surveyCategory2Question1TranslationEn = new SurveyQuestionTranslation();
        $surveyCategory2Question1TranslationEn->setTranslatable($surveyCategory2Question1);
        $surveyCategory2Question1TranslationEn->setLocale('en');
        $surveyCategory2Question1TranslationEn->setHelp('help category Security question 1 EN');
        $surveyCategory2Question1TranslationEn->setLabel('label category Security question 1 EN');

        //$surveyCategory2Question1TranslationEs
        $surveyCategory2Question1TranslationEs = new SurveyQuestionTranslation();
        $surveyCategory2Question1TranslationEs->setTranslatable($surveyCategory2Question1);
        $surveyCategory2Question1TranslationEs->setLocale('es');
        $surveyCategory2Question1TranslationEs->setHelp('help category seguridad question 1 ES');
        $surveyCategory2Question1TranslationEs->setLabel('label category seguridad question 1 ES');

        //$surveyCategory2Question2
        $surveyCategory2Question2 = new SurveyQuestion();
        $surveyCategory2Question2->setCategory($surveyCategory2);
        $surveyCategory2Question2->setQuestionOrder(2);
        $surveyCategory2Question2->setQuestionType('General');

        //$surveyCategory2Question2TranslationFr
        $surveyCategory2Question2TranslationFr = new SurveyQuestionTranslation();
        $surveyCategory2Question2TranslationFr->setTranslatable($surveyCategory2Question2);
        $surveyCategory2Question2TranslationFr->setLocale('fr');
        $surveyCategory2Question2TranslationFr->setHelp('help category Securite question 2 FR');
        $surveyCategory2Question2TranslationFr->setLabel('label category Securite question 2 FR');

        //$surveyCategory2Question2TranslationEn
        $surveyCategory2Question2TranslationEn = new SurveyQuestionTranslation();
        $surveyCategory2Question2TranslationEn->setTranslatable($surveyCategory2Question2);
        $surveyCategory2Question2TranslationEn->setLocale('en');
        $surveyCategory2Question2TranslationEn->setHelp('help category Security question 2 EN');
        $surveyCategory2Question2TranslationEn->setLabel('label category Security question 2 EN');

        //$surveyCategory2Question2TranslationEs
        $surveyCategory2Question2TranslationEs = new SurveyQuestionTranslation();
        $surveyCategory2Question2TranslationEs->setTranslatable($surveyCategory2Question2);
        $surveyCategory2Question2TranslationEs->setLocale('es');
        $surveyCategory2Question2TranslationEs->setHelp('help category seguridad question 2 ES');
        $surveyCategory2Question2TranslationEs->setLabel('label category seguridad question 2 ES');

        /*
         * Category 3 Qualite
         */

        //$surveyCategory 3
        $surveyCategory3 = new SurveyCategory();
        $surveyCategory3->setSurvey($survey);
        $surveyCategory3->setUpdatedAt(new \DateTime());
        $surveyCategory3->setCategoryOrder(3);

        //$surveyCategory3TranslationFr
        $surveyCategory3TranslationFr = new SurveyCategoryTranslation();
        $surveyCategory3TranslationFr->setTitle('Qualite fr');
        $surveyCategory3TranslationFr->setLocale('fr');
        $surveyCategory3TranslationFr->setTranslatable($surveyCategory3);

        //$surveyCategory3TranslationEn
        $surveyCategory3TranslationEn = new SurveyCategoryTranslation();
        $surveyCategory3TranslationEn->setTitle('Qualite En');
        $surveyCategory3TranslationEn->setLocale('en');
        $surveyCategory3TranslationEn->setTranslatable($surveyCategory3);

        //$surveyCategory3TranslationEs
        $surveyCategory3TranslationEs = new SurveyCategoryTranslation();
        $surveyCategory3TranslationEs->setTitle('Qualite Es');
        $surveyCategory3TranslationEs->setLocale('es');
        $surveyCategory3TranslationEs->setTranslatable($surveyCategory3);


        //$surveyCategory3Question1
        $surveyCategory3Question1 = new SurveyQuestion();
        $surveyCategory3Question1->setCategory($surveyCategory3);
        $surveyCategory3Question1->setQuestionOrder(1);
        $surveyCategory3Question1->setQuestionType('Equipe');

        //$surveyCategory3Question1TranslationFr
        $surveyCategory3Question1TranslationFr = new SurveyQuestionTranslation();
        $surveyCategory3Question1TranslationFr->setTranslatable($surveyCategory3Question1);
        $surveyCategory3Question1TranslationFr->setLocale('fr');
        $surveyCategory3Question1TranslationFr->setHelp('help category Qualite question 1 FR');
        $surveyCategory3Question1TranslationFr->setLabel('label category Qualite question 1 FR');

        //$surveyCategory3Question1TranslationEn
        $surveyCategory3Question1TranslationEn = new SurveyQuestionTranslation();
        $surveyCategory3Question1TranslationEn->setTranslatable($surveyCategory3Question1);
        $surveyCategory3Question1TranslationEn->setLocale('en');
        $surveyCategory3Question1TranslationEn->setHelp('help category Qualite question 1 EN');
        $surveyCategory3Question1TranslationEn->setLabel('label category Qualite question 1 EN');

        //$surveyCategory3Question1TranslationEs
        $surveyCategory3Question1TranslationEs = new SurveyQuestionTranslation();
        $surveyCategory3Question1TranslationEs->setTranslatable($surveyCategory3Question1);
        $surveyCategory3Question1TranslationEs->setLocale('es');
        $surveyCategory3Question1TranslationEs->setHelp('help category Qualite question 1 ES');
        $surveyCategory3Question1TranslationEs->setLabel('label category Qualite question 1 ES');

        /***/

        //persist entities
        $this->em->persist($surveyTranslationFr);
        $this->em->persist($surveyTranslationEn);
        $this->em->persist($surveyTranslationEs);

        //$surveyRubrique1QuestionTranslation

        $this->em->persist($surveyCategoryTranslationFr);
        $this->em->persist($surveyCategoryTranslationEn);
        $this->em->persist($surveyCategoryTranslationEs);

        $this->em->persist($surveyQuestion1TranslationFr);
        $this->em->persist($surveyQuestion1TranslationEn);
        $this->em->persist($surveyQuestion1TranslationEs);

        $this->em->persist($surveyQuestion2TranslationFr);
        $this->em->persist($surveyQuestion2TranslationEn);
        $this->em->persist($surveyQuestion2TranslationEs);

        $this->em->persist($surveyQuestion3TranslationFr);
        $this->em->persist($surveyQuestion3TranslationEn);
        $this->em->persist($surveyQuestion3TranslationEs);

        //$surveyRubrique2QuestionTranslation
        $this->em->persist($surveyCategory2TranslationFr);
        $this->em->persist($surveyCategory2TranslationEn);
        $this->em->persist($surveyCategory2TranslationEs);

        $this->em->persist($surveyCategory2Question1TranslationFr);
        $this->em->persist($surveyCategory2Question1TranslationEn);
        $this->em->persist($surveyCategory2Question1TranslationEs);

        $this->em->persist($surveyCategory2Question2TranslationFr);
        $this->em->persist($surveyCategory2Question2TranslationEn);
        $this->em->persist($surveyCategory2Question2TranslationEs);

        //$surveyRubrique3QuestionTranslation
        $this->em->persist($surveyCategory3TranslationFr);
        $this->em->persist($surveyCategory3TranslationEn);
        $this->em->persist($surveyCategory3TranslationEs);

        $this->em->persist($surveyCategory3Question1TranslationFr);
        $this->em->persist($surveyCategory3Question1TranslationEn);
        $this->em->persist($surveyCategory3Question1TranslationEs);

        //$surveyQuestionRubrique1
        $this->em->persist($surveyQuestion3);
        $this->em->persist($surveyQuestion2);
        $this->em->persist($surveyQuestion1);

        //$surveyQuestionRubrique2
        $this->em->persist($surveyCategory2Question1);
        $this->em->persist($surveyCategory2Question2);

        //$surveyQuestionRubrique3
        $this->em->persist($surveyCategory3Question1);

        $this->em->persist($surveyCategory2);
        $this->em->persist($surveyCategory1);

        $this->em->persist($survey);

        $this->em->flush();
    }
}


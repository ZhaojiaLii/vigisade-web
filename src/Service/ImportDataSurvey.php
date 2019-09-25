<?php

namespace App\Service;

use App\Entity\Survey;
use App\Entity\SurveyCategory;
use App\Entity\SurveyCategoryTranslation;
use App\Entity\SurveyQuestion;
use App\Entity\SurveyQuestionTranslation;
use App\Entity\SurveyTranslation;
use App\Repository\SurveyCategoryRepository;
use App\Repository\SurveyCategoryTranslationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportDataSurvey
{
    private $passwordEncoder;
    private $em;
    private $surveyCategoryTranslationRepository;
    private $surveyCategoryRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em,
        ObjectManager $manager,
        SurveyCategoryTranslationRepository $surveyCategoryTranslationRepository,
        SurveyCategoryRepository $surveyCategoryRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->manager = $manager;
        $this->surveyCategoryTranslationRepository = $surveyCategoryTranslationRepository;
        $this->surveyCategoryRepository = $surveyCategoryRepository;
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getEveData()
    {
        $sql = 'SELECT 
c.nom as categorie_nom,
q.type as question_type,
q.nom as question_nom,
q.description as question_description 
FROM eve_interne_question q 
JOIN eve_interne_categorie c ON q.id_categorie = c.id';

        $conn = $this->em->getConnection();
        $data = $conn->prepare($sql);
        $data->execute();
        return $data->fetchAll();
    }

    /**
     * @param $output
     * @throws \Doctrine\DBAL\DBALException
     */
    public function indexEve($output)
    {
        $filenameEveQuestion = __DIR__ . '/../../import/atlas_sade_eve_interne_question.sql';
        $filenameEveCategory = __DIR__ . '/../../import/atlas_sade_eve_interne_categorie.sql';

        if (!file_exists($filenameEveQuestion) || !file_exists($filenameEveCategory)) {
            //throw new \Exception('File does not exist in vigisade-web/import ');
            $output->writeln("<error>The File `atlas_sade_eve_interne_question.sql` or `atlas_sade_eve_interne_categorie.sql` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        // Execute atlas_sade_eve_interne_categorie.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEveCategory));

        // Execute atlas_sade_eve_interne_question.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEveQuestion));

        $this->em->flush();

        $questionData = $this->getEveData();

        $output->writeln([
            '',
            '==========================================================================================================',
            '===================================== <question>Import questions from EVE</question> =====================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($questionData));
        $progressBar->start();

        // Survey
        $survey = new Survey();
        $survey->setTeam('Equipe');
        $survey->setUpdatedAt(new \DateTime());

        $this->manager->persist($survey);
        $this->manager->flush();

        //SurveyTranslation
        $surveyTranslation = new SurveyTranslation();
        $surveyTranslation->setTranslatable($survey);
        $surveyTranslation->setTitle('Télécoms');
        $surveyTranslation->setBestPracticeLabel('Avez-vous identifié une pratique remarquable au cours de cette visite');
        $surveyTranslation->setLocale('fr');

        $this->manager->persist($surveyTranslation);
        $this->manager->flush();

        foreach ($questionData as $value) {

            $surveyCategoryTranslation = $this->em
                ->getRepository(SurveyCategoryTranslation::class)->findOneBy(['title' => $value['categorie_nom']]);

            if (!$surveyCategoryTranslation) {
                // SurveyCategory
                $surveyCategory = new SurveyCategory();
                $surveyCategory->setSurvey($survey);
                $surveyCategory->setCategoryOrder(1);
                $surveyCategory->setUpdatedAt(new \DateTime());

                $this->manager->persist($surveyCategory);
                $this->manager->flush();

                // SurveyCategoryTranslation
                $surveyCategoryTranslation = new SurveyCategoryTranslation();
                $surveyCategoryTranslation->setTranslatable($surveyCategory);
                $surveyCategoryTranslation->setTitle($value['categorie_nom']);
                $surveyCategoryTranslation->setLocale('fr');

                $this->manager->persist($surveyCategoryTranslation);
                $this->manager->flush();
            } else {
                $surveyCategory = $this->surveyCategoryRepository->findOneBy(['id' => $surveyCategoryTranslation->getTranslatable()->getId()]);
            }


            // SurveyQuestion
            $surveyQuestion = new SurveyQuestion();
            $surveyQuestion->setCategory($surveyCategory);

            if ($value['question_type'] == 'multiple') {
                $surveyQuestion->setQuestionType('Equipe');
            } else if ($value['question_type'] == 'simple') {
                $surveyQuestion->setQuestionType('Général');
            }

            $surveyQuestion->setQuestionOrder(1);

            $this->manager->persist($surveyQuestion);
            $this->manager->flush();

            // SurveyQuestionTranslation
            $surveyQuestionTranslation = new SurveyQuestionTranslation();
            $surveyQuestionTranslation->setTranslatable($surveyQuestion);
            $surveyQuestionTranslation->setLabel($value['question_nom']);
            $surveyQuestionTranslation->setHelp($value['question_description']);
            $surveyQuestionTranslation->setLocale('fr');

            $this->manager->persist($surveyQuestionTranslation);
            $this->manager->flush();

            $progressBar->advance();
            echo "  question ID " . $surveyQuestion->getId() . " nom " . $surveyQuestionTranslation->getLabel() . " Succes ";
        }

        $progressBar->finish();
        $output->writeln(['', '', '<comment>Success !</comment>']);
    }
}

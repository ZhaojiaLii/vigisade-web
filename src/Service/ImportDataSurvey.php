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
     * @param int $categoryId
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getEveQuestionsByCategory(int $categoryId)
    {
        $sql = "SELECT 
q.type as question_type,
q.nom as question_nom,
q.description as question_description 
FROM eve_interne_question q 
WHERE q.id_categorie = $categoryId";

        $conn = $this->em->getConnection();
        $data = $conn->prepare($sql);
        $data->execute();
        return $data->fetchAll();
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getEveCategories()
    {
        $sql = 'SELECT 
q.id as categorie_id,
q.nom as categorie_nom
FROM eve_interne_categorie q';

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

        $eveCategories = $this->getEveCategories();

        $output->writeln([
            '',
            '==========================================================================================================',
            '===================================== <question>Import questions from EVE</question> =====================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($eveCategories));
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

        foreach ($eveCategories as $value) {

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

            $eveQuestions = $this->getEveQuestionsByCategory($value['categorie_id']);
            foreach($eveQuestions as $eveQuestion) {
                // SurveyQuestion
                $surveyQuestion = new SurveyQuestion();
                $surveyQuestion->setCategory($surveyCategory);

                if ($eveQuestion['question_type'] == 'multiple') {
                    $surveyQuestion->setQuestionType('Equipe');
                } else if ($eveQuestion['question_type'] == 'simple') {
                    $surveyQuestion->setQuestionType('Général');
                }

                $surveyQuestion->setQuestionOrder(1);

                $this->manager->persist($surveyQuestion);
                $this->manager->flush();

                // SurveyQuestionTranslation
                $surveyQuestionTranslation = new SurveyQuestionTranslation();
                $surveyQuestionTranslation->setTranslatable($surveyQuestion);
                $surveyQuestionTranslation->setLabel($eveQuestion['question_nom']);
                $surveyQuestionTranslation->setHelp($eveQuestion['question_description']);
                $surveyQuestionTranslation->setLocale('fr');

                $this->manager->persist($surveyQuestionTranslation);
                $this->manager->flush();
            }


            $progressBar->advance();
            echo "  question ID " . $surveyCategory->getId() . " nom " . $surveyCategoryTranslation->getTitle() . " Succes ";
        }

        $progressBar->finish();
        $output->writeln(['', '', '<comment>Success !</comment>']);
    }
}

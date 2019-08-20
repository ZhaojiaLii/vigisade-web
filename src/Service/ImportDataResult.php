<?php

namespace App\Service;

use App\Entity\CorrectiveAction;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Entity\User;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use App\Repository\UserRepository;
use App\Repository\SurveyRepository;
use App\Repository\SurveyQuestionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Tests\DependencyInjection\Compiler\FormatListenerRulesPassTest;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportDataResult
{
    private $passwordEncoder;
    private $em;
    private $directionRepository;
    private $areaRepository;
    private $entityRepository;
    private $userRepository;
    private $surveyRepository;
    private $surveyQuestionRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $em,
                                DirectionRepository $directionRepository,
                                AreaRepository $areaRepository,
                                EntityRepository $entityRepository,
                                UserRepository $userRepository,
                                SurveyRepository $surveyRepository,
                                SurveyQuestionRepository $surveyQuestionRepository,
                                ObjectManager $manager){
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
        $this->userRepository = $userRepository;
        $this->surveyRepository = $surveyRepository;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
        $this->manager = $manager;
    }

    protected function loadFile($filename)
    {
        return IOFactory::load($filename);
    }

    protected function readFile($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'ods':
                $reader = new ReaderOds();
                break;
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            case 'csv':
                $reader = new ReaderCsv();
                break;
            default:
                throw new \Exception('Invalid extension');
        }
        $reader->setReadDataOnly(true);
        return $reader->load($filename);
    }

    protected function createDataFromSpreadsheet($spreadsheet)
    {
        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle(); // feuille 1
            $data[$worksheetTitle] = [
                'columnNames' => [],
                'columnValues' => [],
            ];

            foreach ($worksheet->getRowIterator() as $row) {

                $rowIndex = $row->getRowIndex();
                if ($rowIndex > 2) {
                    $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop over all cells, even if it is not set
                foreach ($cellIterator as $cell) {
                    if ($rowIndex === 2) {
                        $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
                    }
                    if ($rowIndex > 2) {
                        $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param $output
     * @throws \Exception
     */
    public function indexGoogleForms($output)
    {
        $filename = __DIR__ . '/../../import/googleForms_resuls.xlsx';

        if (!file_exists($filename)) {
            //throw new \Exception('File does not exist in vigisade-web/import ');
            $output->writeln("<error>The File `googleForms_resuls.xlsx` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        /**
         * Must increase maximum `memory limite` to 64MB in php.ini
         */
        ini_set('memory_limit', '-1'); // this line take unlimited memory usage of server

        $spreadsheet = $this->readFile($filename);
        $output->writeln([
            '<comment> # Read Data from file `googleForms_resuls.xlsx` Onglet `BASE VIGISADE` and `Suivi des actions` Succes </comment>',
            '',
        ]);
        $data = $this->createDataFromSpreadsheet($spreadsheet);

        $dataHorsNucleaireValues = $data['BASE VIGISADE']['columnValues'];
        //$dataNucleaireValues = $data['BASE NUCLEAIRE']['columnValues'];
        $dataActionsValues = $data['Suivi des actions']['columnValues'];

        $output->writeln([
            '',
            '==========================================================================================================',
            '================ <question>Import Result and Actions Correctives – Hors Nucléaire from GoogleForms</question> =================',
            '==========================================================================================================',
            '',
        ]);



        $progressBar = new ProgressBar($output, count($dataHorsNucleaireValues));
        $progressBar->start();

        $actionCorrectives = [];
        foreach ($dataHorsNucleaireValues as $data) {
            if (!empty($data[1])) {

                $date = PHPExcel_Style_NumberFormat::toFormattedString($data[1], 'd/m/Y');
                $date

                dump($data[0], $date);
                die;
                // Result
                $result = new Result();
                $result->setDate(new \DateTime($data[0])); // Colonne Horodateur
                $result->setPlace(!empty($data[3]) ? $data[3] : ""); // Colonne Lieu du chantier
                $result->setClient($data[4]); // Colonne Client
                $result->setValidated('Terminé'); //« Terminé »
                $result->setBestPracticeType(null); // null
                $result->setBestPracticeDone(2); //2
                $result->setBestPracticeComment(null); // null
                $result->setBestPracticePhoto(null); // null
                $result->setSurvey($this->surveyRepository->find(1)); // survey_id = 1
                $result->setUser($this->userRepository->findby(['email' => $data[1]]) ? $this->userRepository->findby(['email' => $data[1]])[0] : null); // Adresse e-mail == user.email pour obtenir user.id
                $result->setDirection($this->directionRepository->findby(['name' => $data[88]]) ? $this->directionRepository->findby(['name' => $data[88]])[0] : null); // colonne Zone
                $result->setArea($this->areaRepository->findby(['name' => $data[87]]) ? $this->areaRepository->findby(['name' => $data[87]])[0] : null); // colonne DR
                $result->setEntity($this->entityRepository->findby(['name' => $data[2]]) ? $this->entityRepository->findby(['name' => $data[2]])[0] : null); // colonne Centre de travaux

                // Result Question
                $notationColonne = 6;
                $commentColonne = 7;
                $photoColonne = 8;

                for ($i = 1; $i <= 10; $i++) {
                    $resultQuestion = new ResultQuestion();
                    $resultQuestion->setComment($data[$commentColonne] ? $data[$commentColonne] : ""); // colonne comment question 1
                    if (!is_string($data[$notationColonne])) {
                        $data[$notationColonne] = (int)$data[$notationColonne];
                    } // les valeurs sont de type float
                    /*
                     * 1 = Notation conforme
                     * 2 = Notation Sans objet / Non vu
                     * 3 = Notation non conforme
                     * 4 = Notation Action corrective
                     */
                    if ($data[$notationColonne] === 3 || $data[$notationColonne] === "Non") {
                        $resultQuestion->setNotation(1);
                    } else if ($data[$notationColonne] === 0) {
                        $resultQuestion->setNotation(2);
                    } else if (($data[$notationColonne] === 2) || ($data[$notationColonne] === "Oui et traité immédiatement  (préciser)")
                        || ($data[$notationColonne] === "Oui (préciser), Non")) {
                        $resultQuestion->setNotation(3);
                    } else if ($data[$notationColonne] === 1 || $data[$notationColonne] === "Oui et reste à traiter (préciser)") {
                        $resultQuestion->setNotation(4);
                    }

                    $resultQuestion->setQuestion($this->surveyQuestionRepository->find($i)); //Question_id== 1 à 10
                    $resultQuestion->setPhoto($data[$photoColonne]); // colonne Photos ou autres - E.P.C
                    $resultQuestion->setTeamMembers(null); // null
                    $result->addQuestion($resultQuestion);

                    $notationColonne += 3;
                    $commentColonne += 3;
                    $photoColonne += 3;
                }

                // Result team member
                if (!empty($data[36])) { //responsable chantier
                    $resultTeamMember = new ResultTeamMember();
                    $resultTeamMember->setFirstName("");
                    $resultTeamMember->setLastName($data[36]); //colonne responsable chantier
                    $resultTeamMember->setRole("Responsable Chantier");
                    $result->addTeamMember($resultTeamMember);
                }
            }
            $this->manager->persist($result);
            $this->manager->flush();
            $progressBar->advance();
            echo "  results ID " . $result->getId() . " Succes ";

            //Action Corrective
            $resultQuestions = $this->manager
                ->getRepository(ResultQuestion::class)
                ->findBy(['result' => $result->getId()]);

            if ($resultQuestions) {
                foreach ($resultQuestions as $resultQuestion) {
                    if ($resultQuestion->getNotation() === 4) {
                        $actionCorrectives [] = [
                            'surveyQuestion' => $resultQuestion->getQuestion(),
                            'resultQuestion' => $resultQuestion,
                            'result' => $result,
                            'result_user' => $result->getUser(),
                            'result_direction' => $result->getDirection(),
                            'result_area' => $result->getArea(),
                            'result_entity' => $result->getEntity(),
                            'code' => $data[89]
                        ];
                    }
                }
            }
        }
        $progressBar->finish();
        $output->writeln(['', '', '<comment>Results, resultQuestion and TeamMember are successfully generated file `googleForms_resuls.xlsx` Onglet `BASE VIGISADE`  !</comment>', '', '']);

        //Action Corrective
        $progressBar = new ProgressBar($output, count($dataActionsValues));
        $progressBar->start();
        foreach ($dataActionsValues as $dataAction) { // form data excel
            foreach ($actionCorrectives as $action) {
                if ($dataAction[2] === $action['code']) {
                    $correctiveAction = new CorrectiveAction();
                    $correctiveAction->setUser($action['result_user']); // Récupère l’information de la table result.user_id pour la ligne correspondante
                    if ($dataAction[3] === "Situation traitée le jour de la visite" ||
                        $dataAction[3] === "Situation traitée après le jour de la visite" ||
                        $dataAction[3] === "Après changement méthode" ||
                        $dataAction[3] === "Situation traité après le jour de la visite" ||
                        $dataAction[3] === "Situation traité le même jour de la visite" ||
                        $dataAction[3] === "Situation traité le jour de la visite"
                    ) {
                        $correctiveAction->setStatus('Corrigé');
                    } else {
                        $correctiveAction->setStatus('A traiter');
                    }
                    $correctiveAction->setQuestion($action['surveyQuestion']); //Récupère l’information de la table question_id pour la ligne correspondante
                    $correctiveAction->setResult($action['result']); // Récupère l’information de la table result pour la ligne correspondante
                    $correctiveAction->setResultQuestion($action['resultQuestion']);//$resultQuestion => array
                    $correctiveAction->setDirection($action['result_direction']); //Récupère l’information de la table result pour la ligne correspondante
                    $correctiveAction->setArea($action['result_area']); //Récupère l’information de la table result pour la ligne correspondante
                    $correctiveAction->setEntity($action['result_entity']); //Récupère l’information de la table result pour la ligne correspondante
                    $correctiveAction->setCommentQuestion($dataAction[4]);
                    $correctiveAction->setImage(!empty($dataAction[5]) ? $dataAction[5] : '');
                }
            }
            $this->manager->persist($correctiveAction);
            $this->manager->flush();
            $progressBar->advance();
            echo "  Action corrective ID " . $correctiveAction->getId() . " Succes ";
        }
        $progressBar->finish();
        $output->writeln(['', '', '<comment> Corrective action are successfully generated from file `googleForms_resuls.xlsx` Onglet `Suivi des actions` GoogleForm !</comment>']);

        if(!empty($actionCorrectives)){

            $output->writeln(['', '', '<info>==========================================================================================================</info>']);
            $arrayRows = [];
            $i=0;
            foreach ($actionCorrectives as $value) {
                if($value['code'] === null) {
                    $arrayRows[] = [$value['resultQuestion']->getId(), 'notation = ' . $value['resultQuestion']->getNotation().'( => Action corrective)', 'code = null en colonne 120'];
                    $i++;
                }
            }
            if($i!=0){
                $table = new Table($output);
                $table
                    ->setHeaders(['ID result_Question', 'notation', 'code = null'])
                    ->setRows($arrayRows);
                $table->render();

                $output->writeln('<error>==================================== '.$i.' Corrective Action(s) not saved=====================================</error>');
            }


        }
    }

    /**
     * @param $output
     * @throws \Exception
     */
    public function NucleaireGoogleForms($output)
    {
        $filename = __DIR__ . '/../../import/googleForms_resuls.xlsx';

        if (!file_exists($filename)) {
            //throw new \Exception('File does not exist in vigisade-web/import ');
            $output->writeln("<error>The File `googleForms_resuls.xlsx` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        /**
         * Must increase maximum `memory limite` to 64MB in php.ini
         */
        ini_set('memory_limit', '-1'); // this line take unlimited memory usage of server

        $spreadsheet = $this->readFile($filename);
        $output->writeln([
            '<comment> # Read Data from file `googleForms_resuls.xlsx` Onglet `BASE NUCLEAIRE` and `Suivi des actions`Succes </comment>',
            '',
        ]);
        $data = $this->createDataFromSpreadsheet($spreadsheet);

        $dataNucleaireValues = $data['BASE NUCLEAIRE']['columnValues'];
        $dataActionsValues = $data['Suivi des actions']['columnValues'];

        $output->writeln([
            '',
            '==========================================================================================================',
            '================== <question>Import Result and Actions Correctives – Nucléaire from GoogleForms</question> ===================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($dataNucleaireValues));
        $progressBar->start();

        $actionCorrectives = [];
        foreach ($dataNucleaireValues as $data) {
            if (!empty($data[1])) {
                // Result
                $result = new Result();
                $result->setDate(new \DateTime()); // Colonne Horodateur
                $result->setPlace(!empty($data[3]) ? $data[3] : ""); // Colonne Lieu du chantier
                $result->setClient($data[4]); // Colonne Client
                $result->setValidated('Terminé'); //« Terminé »
                $result->setBestPracticeType(null); // null
                $result->setBestPracticeDone(2); //2
                $result->setBestPracticeComment(null); // null
                $result->setBestPracticePhoto(null); // null
                $result->setSurvey($this->surveyRepository->find(2)); // survey_id = 2
                $result->setUser($this->userRepository->findby(['email' => $data[1]]) ? $this->userRepository->findby(['email' => $data[1]])[0] : null); // Adresse e-mail == user.email pour obtenir user.id
                //$result->setDirection($this->directionRepository->find(6)); // direction_id = 6
                $result->setDirection($this->directionRepository->findby(['name' => $data[119]]) ? $this->directionRepository->findby(['name' => $data[119]])[0] : null); // colonne Zone
                //$result->setArea($this->areaRepository->findby(['name' => $data[118], 'direction' => 6]) ? $this->areaRepository->findby(['name' => $data[118], 'direction' => 6])[0] : null); // colonne DR
                $result->setArea($this->areaRepository->findby(['name' => $data[118]]) ? $this->areaRepository->findby(['name' => $data[118]])[0] : null); // colonne DR
                $result->setEntity($this->entityRepository->findby(['name' => $data[2]]) ? $this->entityRepository->findby(['name' => $data[2]])[0] : null); // colonne Centre de travaux

                // Result Question
                $notationColonne = 6;
                $commentColonne = 7;
                $photoColonne = 8;

                for ($i = 11; $i <= 24; $i++) {
                    $resultQuestion = new ResultQuestion();
                    $resultQuestion->setComment($data[$commentColonne] ? $data[$commentColonne] : ""); // colonne comment question 1
                    if (!is_string($data[$notationColonne])) {
                        $data[$notationColonne] = (int)$data[$notationColonne];
                    } // les valeurs sont de type float
                    /*
                     * 1 = Notation conforme
                     * 2 = Notation Sans objet / Non vu
                     * 3 = Notation non conforme
                     * 4 = Notation Action corrective
                     */
                    if ($data[$notationColonne] === 3 || $data[$notationColonne] === "Non") {
                        $resultQuestion->setNotation(1);
                    } else if ($data[$notationColonne] === 0) {
                        $resultQuestion->setNotation(2);
                    } else if (($data[$notationColonne] === 2) || ($data[$notationColonne] === "Oui et traité immédiatement  (préciser)")
                        || ($data[$notationColonne] === "Oui (préciser), Non")) {
                        $resultQuestion->setNotation(3);
                    } else if ($data[$notationColonne] === 1 || $data[$notationColonne] === "Oui et reste à traiter (préciser)") {
                        $resultQuestion->setNotation(4);
                    }

                    $resultQuestion->setQuestion($this->surveyQuestionRepository->find($i)); //Question_id== 11 à 24
                    $resultQuestion->setPhoto($data[$photoColonne]); // colonne Photos ou autres
                    $resultQuestion->setTeamMembers(null); // null
                    $result->addQuestion($resultQuestion);

                    $notationColonne += 3;
                    $commentColonne += 3;
                    $photoColonne += 3;
                }

                // Result team member
                if (!empty($data[48])) { // colonne responsable chantier
                    $resultTeamMember = new ResultTeamMember();
                    $resultTeamMember->setFirstName("");
                    $resultTeamMember->setLastName($data[48]); //colonne responsable chantier
                    $resultTeamMember->setRole("Responsable Chantier");
                    $result->addTeamMember($resultTeamMember);
                }
            }
            $this->manager->persist($result);
            $this->manager->flush();
            $progressBar->advance();
            echo "  results ID " . $result->getId() . " Succes ";

            //Action Corrective
            $resultQuestions = $this->manager
                ->getRepository(ResultQuestion::class)
                ->findBy(['result' => $result->getId()]);

            if ($resultQuestions) {
                foreach ($resultQuestions as $resultQuestion) {
                    if ($resultQuestion->getNotation() === 4) {
                        $actionCorrectives [] = [
                            'surveyQuestion' => $resultQuestion->getQuestion(),
                            'resultQuestion' => $resultQuestion,
                            'result' => $result,
                            'result_user' => $result->getUser(),
                            'result_direction' => $result->getDirection(),
                            'result_area' => $result->getArea(),
                            'result_entity' => $result->getEntity(),
                            'code' => $data[120]
                        ];
                    }
                }
            }
        }
        $progressBar->finish();
        $output->writeln(['', '', '<comment>Results, resultQuestion and TeamMember are successfully generated file `googleForms_resuls.xlsx` Onglet `BASE NUCLEAIRE`  !</comment>', '', '']);

        //Action Corrective
        $arrayActionNull = [];
        if($actionCorrectives){
            $progressBar = new ProgressBar($output, count($dataActionsValues));
            $progressBar->start();
            foreach ($dataActionsValues as $dataAction) { // form data excel Suivi des actions

                foreach ($actionCorrectives as $action) { // BASE NUCLEAIRE
                    if($action['code'] != null){
                        if ($dataAction[2] === $action['code']) {
                            $correctiveAction = new CorrectiveAction();
                            $correctiveAction->setUser($action['result_user']); // Récupère l’information de la table result.user_id pour la ligne correspondante
                            if ($dataAction[3] === "Situation traitée le jour de la visite" ||
                                $dataAction[3] === "Situation traitée après le jour de la visite" ||
                                $dataAction[3] === "Après changement méthode" ||
                                $dataAction[3] === "Situation traité après le jour de la visite" ||
                                $dataAction[3] === "Situation traité le même jour de la visite" ||
                                $dataAction[3] === "Situation traité le jour de la visite"
                            ) {
                                $correctiveAction->setStatus('Corrigé');
                            } else {
                                $correctiveAction->setStatus('A traiter');
                            }
                            $correctiveAction->setQuestion($action['surveyQuestion']); //Récupère l’information de la table question_id pour la ligne correspondante
                            $correctiveAction->setResult($action['result']); // Récupère l’information de la table result pour la ligne correspondante
                            $correctiveAction->setResultQuestion($action['resultQuestion']);//$resultQuestion => array
                            $correctiveAction->setDirection($action['result_direction']); //Récupère l’information de la table result pour la ligne correspondante
                            $correctiveAction->setArea($action['result_area']); //Récupère l’information de la table result pour la ligne correspondante
                            $correctiveAction->setEntity($action['result_entity']); //Récupère l’information de la table result pour la ligne correspondante
                            $correctiveAction->setCommentQuestion($dataAction[4]);
                            $correctiveAction->setImage(!empty($dataAction[5]) ? $dataAction[5] : '');
                            $this->manager->persist($correctiveAction);
                            $this->manager->flush();
                            $progressBar->advance();
                            echo "  Action corrective ID " . $correctiveAction->getId() . " Succes ";
                        }
                    }
                }
            }
            $progressBar->finish();
            $output->writeln(['', '', '<comment> Corrective action are successfully generated from file `googleForms_resuls.xlsx` Onglet `Suivi des actions`GoogleForm !</comment>']);

            if(!empty($actionCorrectives)){

                $output->writeln(['', '', '<error>==========================================================================================================</error>']);
                $arrayRows = [];
                $i=0;
                foreach ($actionCorrectives as $value) {
                    if($value['code'] === null) {
                        $arrayRows[] = [$value['resultQuestion']->getId(), 'notation = ' . $value['resultQuestion']->getNotation().'( => Action corrective)', 'code = null en colonne 120'];
                        $i++;
                    }
                }
                $table = new Table($output);
                $table
                    ->setHeaders(['ID result_Question', 'notation', 'code = null'])
                    ->setRows($arrayRows);
                $table->render();

                $output->writeln('<error>==================================== '.$i.' Corrective Action(s) not saved=====================================</error>');

            }


        }

    }
}

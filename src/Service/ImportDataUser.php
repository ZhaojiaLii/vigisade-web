<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AreaRepository;
use App\Repository\DirectionRepository;
use App\Repository\EntityRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportDataUser
{
    private $passwordEncoder;
    private $em;
    private $directionRepository;
    private $areaRepository;
    private $entityRepository;
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $em,
                                DirectionRepository $directionRepository,
                                AreaRepository $areaRepository,
                                EntityRepository $entityRepository,
                                UserRepository $userRepository,
                                ObjectManager $manager){
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->directionRepository = $directionRepository;
        $this->areaRepository = $areaRepository;
        $this->entityRepository = $entityRepository;
        $this->userRepository = $userRepository;
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
     * @Route("/import/googleForms", name="import_googleForms")
     */
    public function indexGoogleForms($output)
    {
        $filename = __DIR__ . '/../../import/googleForms_users.xlsx';

        if (!file_exists($filename)) {
            //throw new \Exception('File does not exist in vigisade-web/import ');
            $output->writeln("<error>The File `googleForms_users.xlsx` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);
        $dataUserValues = $data['Feuille 1']['columnValues'];

        $output->writeln([
            '',
            '==========================================================================================================',
            '===================================== <question>Import users from GoogleForms</question> ======================================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($dataUserValues));
        $progressBar->start();

        $usersNotSaved = [];
        foreach ($dataUserValues as $value){
            if($this->entityRepository->getEntityByName($value[1]) === false ){
                if(empty($value[1])){
                    $usersNotSaved [] = ['email' => $value[8], 'entity' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[0], 'entity' => $value[1]];
                }
            } elseif ($this->directionRepository->getDirectionByName($value[3]) === false){
                if(empty($value[3])){
                    $usersNotSaved [] = ['email' => $value[8], 'direction' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[0], 'direction' => $value[3]];
                }
            } elseif ($this->areaRepository->getAreaByName($value[2]) === false){
                if(empty($value[2])){
                    $usersNotSaved [] = ['email' => $value[8], 'area' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[0], 'area' => $value[2]];
                }
            } else {
                $user = new User();
                $user->setEmail($value[0]);
                $user->setDirection($this->directionRepository->getDirectionByName($value[3]));
                $user->setArea($this->areaRepository->getAreaByName($value[2]));
                $user->setEntity($this->entityRepository->getEntityByName($value[1]));
                $user->setFirstName('');
                $user->setLastname('');
                $user->setRoles(['ROLE_CONDUCTEUR']);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    random_bytes(8)
                ));
                $user->setCompletedProfile(($user->getDirection() && $user->getArea() && $user->getEntity())? true : false);
                $user->setActif(1);
                $user->setUpdatedAt(new \DateTime());
                $user->setLanguage('fr');
                $this->manager->persist($user);
                $this->manager->flush();

                $progressBar->advance();
                echo "  user ID " . $user->getId() . " email " . $user->getEmail() . " Succes ";
            }
        }
        $progressBar->finish();
        $output->writeln(['', '', '<comment>Users successfully generated from GoogleForm !</comment>']);

        if(!empty($usersNotSaved)){
            $output->writeln(['','','<error>========================================= '.count($usersNotSaved).' User(s) not saved ==========================================</error>']);
            $arrayRows = [];
            foreach ($usersNotSaved as $userNotSaved){
                if(isset($userNotSaved['entity'])){
                    if($userNotSaved['entity'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Entity    : `'.$userNotSaved['entity'].'`'];
                    }else{
                        $arrayRows[] = [$userNotSaved['email'], 'Entity    : `'.$userNotSaved['entity'].'` (NOT EXIST)'];
                    }
                }
                if(isset($userNotSaved['direction'])){
                    if($userNotSaved['direction'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Direction    : `'.$userNotSaved['direction'].'`'];
                    }else{
                        $arrayRows[] = [$userNotSaved['email'], 'Direction : `'.$userNotSaved['direction'].'` (NOT EXIST)'];
                    }
                }
                if(isset($userNotSaved['area'])){
                    if($userNotSaved['area'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Area    : `'.$userNotSaved['area'].'`'];
                    }else {
                        $arrayRows[] = [$userNotSaved['email'], 'Area      : `' . $userNotSaved['area'] . '` (NOT EXIST)'];
                    }
                }
            }
            $table = new Table($output);
            $table
                ->setHeaders(['EMAIL', 'Direction/Area/Entity'])
                ->setRows($arrayRows)
            ;
            $table->render();

            $output->writeln('<error>==========================================================================================================</error>');
        }
    }

    /**
     * @Route("/import/eve", name="import_eve")
     */
    public function indexEve($output)
    {
        $filename = __DIR__ . '/../../import/eve_users.xlsx';

        if (!file_exists($filename)) {
            //throw new \Exception('File does not exist');
            $output->writeln("<error>The File `eve_users.xlsx` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);
        $dataUserValues = $data['Feuil2']['columnValues'];

        $output->writeln([
            '',
            '==========================================================================================================',
            '========================================= <question>Import users from EVE</question> ==========================================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($dataUserValues));
        $progressBar->start();

        $usersNotSaved = [];
        foreach ($dataUserValues as $value) {
            if ($this->entityRepository->getEntityByName($value[3]) === false) {
                if(empty($value[3])){
                    $usersNotSaved [] = ['email' => $value[8], 'entity' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[8], 'entity' => $value[3]];
                }
            } elseif ($this->directionRepository->getDirectionByName($value[2]) === false) {
                if(empty($value[2])){
                    $usersNotSaved [] = ['email' => $value[8], 'direction' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[8], 'direction' => $value[2]];
                }
            } elseif ($this->areaRepository->getAreaByName($value[5]) === false) {
                if(empty($value[5])){
                    $usersNotSaved [] = ['email' => $value[8], 'area' => "null"];
                }else {
                    $usersNotSaved [] = ['email' => $value[8], 'area' => $value[5]];
                }
            } else {
                $user = new User();
                $user->setEmail($value[8]);
                $user->setDirection($this->directionRepository->getDirectionByName($value[2]));
                $user->setArea($this->areaRepository->getAreaByName($value[5]));
                $user->setEntity($this->entityRepository->getEntityByName($value[3]));
                $user->setFirstName($value[1]);
                $user->setLastname($value[0]);
                if($value[4] === null){ $value[4] = 'ROLE_CONDUCTEUR'; }
                if($value[4] === "Conducteur"){ $value[4] = 'ROLE_CONDUCTEUR';}
                if($value[4] === "Manager"){ $value[4] = 'ROLE_MANAGER';}
                if($value[4] === "Administrateur"){ $value[4] = 'ROLE_ADMIN';}
                $user->setRoles([$value[4]]);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    random_bytes(8)
                ));
                $user->setCompletedProfile(($user->getDirection() && $user->getArea() && $user->getEntity())? true : false);
                $user->setActif(($value[7] === "Actif") ? 1 : 0);
                $user->setUpdatedAt(new \DateTime());
                $user->setLanguage('fr');
                $this->manager->persist($user);
                $this->manager->flush();

                $progressBar->advance();
                echo "  user ID " . $user->getId() . " email " . $user->getEmail() . " Succes ";
            }
        }

        /**
         * user brocelia
         */
        $user = new User();
        $user->setEmail('admin_vigisade@brocelia.fr');
        $user->setFirstName('admin');
        $user->setLastname('brocelia');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin@1234'
        ));
        $user->setActif(1);
        $user->setUpdatedAt(new \DateTime());
        $user->setLanguage('fr');
        $user->setCompletedProfile(($user->getDirection() && $user->getArea() && $user->getEntity())? true : false);
        $this->manager->persist($user);
        $this->manager->flush();

        $progressBar->finish();
        $output->writeln(['', '', '<comment>Users successfully generated from eve !</comment>', '']);
        if(!empty($usersNotSaved)){
            $output->writeln(['','','<error>========================================== '.count($usersNotSaved).' User(s) not saved ===========================================</error>']);

            $arrayRows = [];
            foreach ($usersNotSaved as $userNotSaved){
                if(isset($userNotSaved['entity'])){
                    if($userNotSaved['entity'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Entity    : `'.$userNotSaved['entity'].'`'];
                    }else{
                        $arrayRows[] = [$userNotSaved['email'], 'Entity    : `'.$userNotSaved['entity'].'` (NOT EXIST)'];
                    }
                }

                if(isset($userNotSaved['direction'])){
                    if($userNotSaved['direction'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Direction    : `'.$userNotSaved['direction'].'`'];
                    }else{
                        $arrayRows[] = [$userNotSaved['email'], 'Direction : `'.$userNotSaved['direction'].'` (NOT EXIST)'];
                    }
                }
                if(isset($userNotSaved['area'])){
                    if($userNotSaved['area'] === "null"){
                        $arrayRows[] = [$userNotSaved['email'], 'Area    : `'.$userNotSaved['area'].'`'];
                    }else {
                        $arrayRows[] = [$userNotSaved['email'], 'Area      : `' . $userNotSaved['area'] . '` (NOT EXIST)'];
                    }
                }
            }

            $table = new Table($output);
            $table
                ->setHeaders(['EMAIL', 'Direction/Area/Entity'])
                ->setRows($arrayRows)
            ;
            $table->render();

            $output->writeln('<error>==========================================================================================================</error>');
        }
    }
}

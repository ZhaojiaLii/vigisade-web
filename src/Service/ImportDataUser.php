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
    public function indexGoogleForms()
    {
        $filename = __DIR__.'/../../import/googleForms_users.xlsx';

        if (!file_exists($filename)) {
            throw new \Exception('File does not exist');
        }

        //flash table users
        $this->userRepository->RemoveUsers();

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);
        $dataUserValues = $data['Feuille 1']['columnValues'];

        foreach ($dataUserValues as $value){

            $user = new User();
            $user->setEmail($value[0]);
            $user->setDirection($this->directionRepository->getDirectionByName($value[3]));
            $user->setArea($this->areaRepository->getAreaByName($value[2]));
            $user->setEntity($this->entityRepository->getEntityByName($value[1]));
            $user->setFirstName('');
            $user->setLastname('');
            $user->setRoles(['Conducteur']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                random_bytes(8)
            ));
            $user->setActif(1);
            $user->setUpdatedAt(new \DateTime());
            $this->manager->persist($user);
            $this->manager->flush();
            echo $user->getId()."user ".$value[0]." Succes </br>";

        }
    }

    /**
     * @Route("/import/eve", name="import_eve")
     */
    public function indexEve()
    {
        $filename = __DIR__.'/../../import/eve_users.xlsx';

        //flash table users
        $this->userRepository->RemoveUsers();

        if (!file_exists($filename)) {
            throw new \Exception('File does not exist');
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);
        $dataUserValues = $data['Feuil2']['columnValues'];
       
        foreach ($dataUserValues as $value){

            $user = new User();
            $user->setEmail($value[8]);
            $user->setDirection($this->directionRepository->getDirectionByName($value[2]));
            $user->setArea($this->areaRepository->getAreaByName($value[5]));
            $user->setEntity($this->entityRepository->getEntityByName($value[3]));
            $user->setFirstName($value[1]);
            $user->setLastname($value[0]);
            $user->setRoles([$value[4]]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                random_bytes(8)
            ));

            $user->setActif(($value[7] === "Actif") ? 1 : 0);
            $user->setUpdatedAt(new \DateTime());
            $this->manager->persist($user);
            $this->manager->flush();
            echo $user->getId()." user ".$value[8]." Succes </br>";
        }
    }
}

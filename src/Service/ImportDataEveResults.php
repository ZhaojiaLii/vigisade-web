<?php

namespace App\Service;

use App\Entity\CorrectiveAction;
use App\Entity\Direction;
use App\Entity\Result;
use App\Entity\ResultQuestion;
use App\Entity\ResultTeamMember;
use App\Entity\User;
use App\Repository\DirectionRepository;
use App\Repository\ResultRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportDataEveResults
{
    private $em;

    private $resultRepository;

    public function __construct(
        EntityManagerInterface $em,
        ResultRepository $resultRepository,
        ObjectManager $manager
    ) {
        $this->em = $em;
        $this->resultRepository = $resultRepository;
        $this->manager = $manager;
    }

    /**
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getEveData()
    {
        $sql = 'SELECT 
c.controleur as controle_controleur,
c.date_controle as controle_date,
c.chantier as controle_chantier,
c.nom_entreprise as controle_nom_entreprise,
c.etat as controle_etat,
c.ok as controle_ok,
c.ko as controle_ko,
c.nv as controle_nv,
c.ac as controle_ac,
c.commentaire as controle_commentaire,
i.nom as equipe_nom,
i.prenom as equipe_prenom,
p.nom as pj_nom
FROM eve_securite_controle c
JOIN eve_interne_equipe i ON i.id_controle = c.id
JOIN eve_pj_interne_controle p ON p.id_controle = c.id';

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
        $filenameEveInterneEquipe = __DIR__ . '/../../import/atlas_sade_eve_interne_equipe.sql';
        $filenameEvePjInterneControle = __DIR__ . '/../../import/atlas_sade_eve_pj_interne_controle.sql';
        $filenameEveSecuriteControle = __DIR__ . '/../../import/atlas_sade_eve_securite_controle.sql';
        $filenameEveSuiviNc = __DIR__ . '/../../import/atlas_sade_eve_suivi_nc.sql';

        if (!file_exists($filenameEveInterneEquipe) || !file_exists($filenameEvePjInterneControle) || !file_exists($filenameEveSecuriteControle) || !file_exists($filenameEveSuiviNc)) {
            //throw new \Exception('File does not exist in vigisade-web/import ');
            $output->writeln("<error>The File `atlas_sade_eve_interne_equipe.sql` or `atlas_sade_eve_pj_interne_controle.sql` or `atlas_sade_eve_securite_controle.sql` or `atlas_sade_eve_suivi_nc.sql` does not exist in `vigisade-web/import` ! </error>");
            return;
        }

        // Execute atlas_sade_eve_interne_equipe.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEveInterneEquipe));

        // Execute atlas_sade_eve_pj_interne_controle.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEvePjInterneControle));

        // Execute atlas_sade_eve_securite_controle.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEveSecuriteControle));

        // Execute atlas_sade_eve_suivi_nc.sql
        $this->em->getConnection()->exec(file_get_contents($filenameEveSuiviNc));

        $this->em->flush();

        $resultsData = $this->getEveData();


        $output->writeln([
            '',
            '==========================================================================================================',
            '===================================== <question>Import results from EVE</question> =====================',
            '==========================================================================================================',
            '',
        ]);

        $progressBar = new ProgressBar($output, count($resultsData));
        $progressBar->start();

        foreach ($resultsData as $value) {

            // Result
            $result = new Result();
            $direction = $this->manager
                ->getRepository(Direction::class)
                ->findBy(['name' => $value['controle_controleur']]);

            if ($direction) {
                $result->setSurvey($direction->getSurvey());
            }

            $controleur = explode(' ', $value['controle_controleur']);

            $user = $this->manager
                ->getRepository(User::class)
                ->findOneBy(['lastname' => $controleur[0], 'firstname' => $controleur[1]]);

            if ($user) {
                $result->setUser($user);
                $result->setDirection($user->getDirection());
                $result->setArea($user->getArea());
                $result->setEntity($user->getEntity());
            }
            $result->setDate(new \DateTime($value['controle_date']));
            $result->setPlace($value['controle_chantier']);
            $result->setClient($value['controle_nom_entreprise']);
            $result->setValidated($value['controle_etat']);
            $result->setBestPracticeDone(2);
            $this->manager->persist($result);
            $this->manager->flush();

            // Result Question
            $resultQuestion = new ResultQuestion();
            $resultQuestion->setResult($result);
            $resultQuestion->setQuestion(1);
            $resultQuestion->setNotation(1);
            $resultQuestion->setComment($value['controle_commentaire']);
            $resultQuestion->setPhoto($value['pj_nom']);
            $this->manager->persist($resultQuestion);
            $this->manager->flush();


            // Result Team Member
            $resultTeamMember = new ResultTeamMember();
            $resultTeamMember->setResult($result);
            $resultTeamMember->setFirstName($controleur[0]);
            $resultTeamMember->setLastName($controleur[1]);
            $resultTeamMember->setRole('');
            $this->manager->persist($resultTeamMember);
            $this->manager->flush();

            // Corrective Action
            $correctiveAction = new CorrectiveAction();
            $correctiveAction->setUser($user);
            $correctiveAction->setQuestion(1);
            $correctiveAction->setResult($result);
            $correctiveAction->setResultQuestion($resultQuestion);
            $correctiveAction->setDirection($result->getDirection());
            $correctiveAction->setArea($result->getArea());
            $correctiveAction->setEntity($result->getEntity());
            $correctiveAction->setStatus("Validé");
            $correctiveAction->setImage(null);
            $correctiveAction->setCommentQuestion('action_realisée');
            $this->manager->persist($correctiveAction);
            $this->manager->flush();


            $progressBar->advance();
            echo "Result Id " . $result->getId();
        }

        $progressBar->finish();
        $output->writeln(['', '', '<comment>Success !</comment>']);
    }
}

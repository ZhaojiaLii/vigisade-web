<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EntityFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $arrayEntites = [
            ["area_id" => "12", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "13", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "13", "name" => "COTTEL Reseaux R2A"],
            ["area_id" => "14", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "14", "name" => "COTTEL Reseaux R2A"],
            ["area_id" => "15", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "15", "name" => "COTTEL Reseaux R2A"],
            ["area_id" => "16", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "16", "name" => "COTTEL Reseaux R2A"],
            ["area_id" => "17", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "18", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "19", "name" => "COTTEL Reseaux GE"],
            ["area_id" => "19", "name" => "COTTEL Reseaux R2A"],
            ["area_id" => "20", "name" => "ETE Reseaux Bassens"],
            ["area_id" => "20", "name" => "ETE Reseaux Orthez"],
            ["area_id" => "20", "name" => "ETE Reseaux Toulouse"],
            ["area_id" => "21", "name" => "ETE Reseaux Bassens"],
            ["area_id" => "21", "name" => "ETE Reseaux Cabries"],
            ["area_id" => "21", "name" => "ETE Reseaux Orthez"],
            ["area_id" => "21", "name" => "ETE Reseaux Siege"],
            ["area_id" => "21", "name" => "ETE Reseaux Toulouse"],
            ["area_id" => "22", "name" => "ETE Reseaux Bassens"],
            ["area_id" => "22", "name" => "ETE Reseaux Cabries"],
            ["area_id" => "22", "name" => "ETE Reseaux Montpellier"],
            ["area_id" => "22", "name" => "ETE Reseaux Orthez"],
            ["area_id" => "22", "name" => "ETE Reseaux Toulouse"],
            ["area_id" => "23", "name" => "ETE Reseaux Bassens"],
            ["area_id" => "23", "name" => "ETE Reseaux Cabries"],
            ["area_id" => "23", "name" => "ETE Reseaux Montpellier"],
            ["area_id" => "23", "name" => "ETE Reseaux Siege"],
            ["area_id" => "23", "name" => "ETE Reseaux Toulouse"],
            ["area_id" => "24", "name" => "ETE Reseaux Bassens"],
            ["area_id" => "24", "name" => "ETE Reseaux Cabries"],
            ["area_id" => "24", "name" => "ETE Reseaux Montpellier"],
            ["area_id" => "24", "name" => "ETE Reseaux Orthez"],
            ["area_id" => "24", "name" => "ETE Reseaux Toulouse"],
            ["area_id" => "25", "name" => "ETE Reseaux Orthez"],
            ["area_id" => "25", "name" => "ETE Reseaux Siege"],
            ["area_id" => "26", "name" => "ETE Reseaux Siege"],
            ["area_id" => "27", "name" => "ETE Reseaux Siege"],
            ["area_id" => "28", "name" => "SST Clamart"],
            ["area_id" => "28", "name" => "SST Nord Elec"],
            ["area_id" => "28", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "28", "name" => "SST Aubergenville"],
            ["area_id" => "28", "name" => "SST Clamart"],
            ["area_id" => "28", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "28", "name" => "SST Ouest Sedentaires"],
            ["area_id" => "28", "name" => "SST Reseau National"],
            ["area_id" => "30", "name" => "SST Aubergenville"],
            ["area_id" => "30", "name" => "SST Clamart"],
            ["area_id" => "30", "name" => "SST Nord BL & BE Cuivre"],
            ["area_id" => "30", "name" => "SST Nord D1 ROCA GC NEGO"],
            ["area_id" => "30", "name" => "SST Nord D2 & BE FTTH"],
            ["area_id" => "30", "name" => "SST Nord D3"],
            ["area_id" => "30", "name" => "SST Nord Elec"],
            ["area_id" => "30", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "30", "name" => "SST Ouest CFO/CFA"],
            ["area_id" => "30", "name" => "SST Ouest FO Grands Projets"],
            ["area_id" => "30", "name" => "SST Reseau National"],
            ["area_id" => "31", "name" => "SST Aubergenville"],
            ["area_id" => "31", "name" => "SST Clamart"],
            ["area_id" => "31", "name" => "SST Direction"],
            ["area_id" => "31", "name" => "SST Nord Elec"],
            ["area_id" => "31", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "31", "name" => "SST Ouest CFO/CFA"],
            ["area_id" => "31", "name" => "SST Ouest FO Grands Projets"],
            ["area_id" => "31", "name" => "SST Ouest RADIO Mobile"],
            ["area_id" => "31", "name" => "SST Reseau National"],
            ["area_id" => "32", "name" => "SST Aubergenville"],
            ["area_id" => "32", "name" => "SST Clamart"],
            ["area_id" => "32", "name" => "SST Nord BL & BE Cuivre"],
            ["area_id" => "32", "name" => "SST Nord D1 ROCA GC NEGO"],
            ["area_id" => "32", "name" => "'SST Nord D2 & BE FTTH"],
            ["area_id" => "32", "name" => "SST Nord Elec"],
            ["area_id" => "32", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "32", "name" => "SST Ouest CFO/CFA"],
            ["area_id" => "32", "name" => "SST Ouest FO Grands Projets"],
            ["area_id" => "32", "name" => "SST Ouest RADIO Mobile"],
            ["area_id" => "32", "name" => "SST Reseau National"],
            ["area_id" => "33", "name" => "SST Aubergenville"],
            ["area_id" => "33", "name" => "SST CHSCT"],
            ["area_id" => "34", "name" => "SST Direction"],
            ["area_id" => "35", "name" => "SST Nord POI & ADMI"],
            ["area_id" => "11", "name" => "CAPILLON"],
            ["area_id" => "11", "name" => "CIEMA"],
            ["area_id" => "11", "name" => "CLAISSE"],
            ["area_id" => "11", "name" => "COCA ATL"],
            ["area_id" => "11", "name" => "COPRED"],
            ["area_id" => "11", "name" => "DPSM"],
            ["area_id" => "11", "name" => "ERCTP"],
            ["area_id" => "11", "name" => "GANTELET"],
            ["area_id" => "11", "name" => "GECITEC"],
            ["area_id" => "11", "name" => "GT Canalisation"],
            ["area_id" => "11", "name" => "GUIGUES"],
            ["area_id" => "11", "name" => "HUMBERT"],
            ["area_id" => "11", "name" => "MERCIER"],
            ["area_id" => "11", "name" => "MIANE ET VINATIER"],
            ["area_id" => "11", "name" => "PICHON"],
            ["area_id" => "11", "name" => "ROCHE"],
            ["area_id" => "11", "name" => "ROUSSEAU"],
            ["area_id" => "11", "name" => "SAT"],
            ["area_id" => "11", "name" => "SATER"],
            ["area_id" => "11", "name" => "SET"],
            ["area_id" => "11", "name" => "SETHA"],
            ["area_id" => "11", "name" => "SFDE TX"],
            ["area_id" => "11", "name" => "SMTP"],
            ["area_id" => "11", "name" => "SNA PROSPERI"],
            ["area_id" => "11", "name" => "SOMEC"],
            ["area_id" => "7", "name" => "Activite nucleaire"],
            ["area_id" => "7", "name" => "Clermont-Ferrand"],
            ["area_id" => "7", "name" => "Dijon"],
            ["area_id" => "7", "name" => "Grand Lyon"],
            ["area_id" => "7", "name" => "Grand-Lyon  Infra"],
            ["area_id" => "7", "name" => "Grenoble"],
            ["area_id" => "7", "name" => "Montagny"],
            ["area_id" => "6", "name" => "Activite nucleaire"],
            ["area_id" => "6", "name" => "Alsace"],
            ["area_id" => "6", "name" => "Metz"],
            ["area_id" => "6", "name" => "Reims"],
            ["area_id" => "5", "name" => "Activite nucleaire"],
            ["area_id" => "5", "name" => "Artois-Lens"],
            ["area_id" => "5", "name" => "Beauvais"],
            ["area_id" => "5", "name" => "Boulogne"],
            ["area_id" => "5", "name" => "Dunkerque"],
            ["area_id" => "5", "name" => "Nord"],
            ["area_id" => "4", "name" => "Activite nucleaire"],
            ["area_id" => "4", "name" => "CLICHY"],
            ["area_id" => "4", "name" => "MEAUX"],
            ["area_id" => "4", "name" => "MELUN"],
            ["area_id" => "4", "name" => "SAINT-OUEN"],
            ["area_id" => "4", "name" => "TRAVAUX NEUFS"],
            ["area_id" => "4", "name" => "WISSOUS"],
            ["area_id" => "2", "name" => "Activite nucleaire"],
            ["area_id" => "2", "name" => "Basse normandie"],
            ["area_id" => "2", "name" => "Seine maritime"],
            ["area_id" => "2", "name" => "Yvelines, Eure et Eure et Loir"],
            ["area_id" => "1", "name" => "Brest"],
            ["area_id" => "1", "name" => "Etancheite"],
            ["area_id" => "1", "name" => "Forages"],
            ["area_id" => "1", "name" => "Nantes"],
            ["area_id" => "1", "name" => "Rennes"],
            ["area_id" => "1", "name" => "Tours/Poitiers"],
            ["area_id" => "8", "name" => "Activite nucleaire"],
            ["area_id" => "8", "name" => "Division 1"],
            ["area_id" => "8", "name" => "Division 2"],
            ["area_id" => "8", "name" => "Laboratoire"],
            ["area_id" => "10", "name" => "DIVISION 2, 4, 5"],
            ["area_id" => "10", "name" => "DIVISION 8"],
            ["area_id" => "10", "name" => "Sce Materiel"],
            ["area_id" => "9", "name" => "Activite nucleaire"],
            ["area_id" => "9", "name" => "Marseille"],
            ["area_id" => "9", "name" => "Montpellier"],
            ["area_id" => "9", "name" => "Nice"],
            ["area_id" => "9", "name" => "Sadertelec"],
            ["area_id" => "9", "name" => "Seyne-sur-mer"],
            ["area_id" => "3", "name" => "Activite nucleaire"],
            ["area_id" => "3", "name" => "Aquitaine"],
            ["area_id" => "3", "name" => "Limousin-Charentes"],
            ["area_id" => "3", "name" => "Mediterranee"],
            ["area_id" => "3", "name" => "Midi-Pyrenees"],
            ["area_id" => "36", "name" => "Activite nucleaire"],
            ["area_id" => "37", "name" => "Activite nucleaire"],
            ["area_id" => "38", "name" => "Activite nucleaire"],
            ["area_id" => "39", "name" => "Activite nucleaire"],
            ["area_id" => "40", "name" => "Activite nucleaire"],
            ["area_id" => "41", "name" => "Activite nucleaire"],
        ];

        foreach ($arrayEntites as $arrayEntity){
            $area = $manager->getRepository(Area::class)->findBy(['id' => $arrayEntity['area_id']]);
            $entity = new Entity();
            $entity->setName($arrayEntity['name']);
            $entity->setArea($area[0]);
            $entity->setEtat(true);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            AreaFixtures::class,
        ];
    }
}

<?php
namespace App\Command;

use App\Entity\Area;
use App\Entity\Direction;
use App\Entity\Entity;
use App\Entity\User;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use App\Service\ImportDataUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:users';

    public function __construct(
        string $name = null,
        ImportDataUser $importDataUser,
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {
        parent::__construct($name);
        $this->importDataUser = $importDataUser;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import users from file eve and googleForms')
            ->setHelp('This command allows you to import users from file xlxs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //check if direction/area/entity are imported and not empty in data
        if(!$this->em->getRepository(Direction::class)->findAll()){
            throw new NotFoundException("You must import data from table `Direction` before import Users ");
        }

        if(!$this->em->getRepository(Entity::class)->findAll()){
            throw new NotFoundException("You must import data from table `Entity` before import Users ");
        }

        if(!$this->em->getRepository(Area::class)->findAll()){
            throw new NotFoundException("You must import data from table `Area`  before import Users ");
        }

        $output->writeln([
            '<info>==========================================================================================================</info>',
            '<info>================================= Users Creator from EVE and googleForms =================================</info>',
            '<info>==========================================================================================================</info>',
            '',
        ]);

        //flash table users
        $this->userRepository->RemoveUsers();

        $output->writeln([
            '',
            '<comment>Flash table User Succes</comment>',
            '',
        ]);

        $output->writeln($this->importDataUser->indexEve($output));
        $output->writeln($this->importDataUser->indexGoogleForms($output));
    }
}

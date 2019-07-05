<?php
namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ImportDataUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:users';

    public function __construct(string $name = null, ImportDataUser $importDataUser, UserRepository $userRepository)
    {
        parent::__construct($name);
        $this->importDataUser = $importDataUser;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import users from file eve and googleForms')
            ->setHelp('This command allows you to import users from file xlxs')
            ->addArgument('data',InputArgument::REQUIRED, 'data from eve ou googleForms')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Users Creator from EVE',
            '=====================================',
            '',
        ]);

        //flash table users
        $this->userRepository->RemoveUsers();

        if($input->getArgument('data')==='eve') {
            $output->writeln($this->importDataUser->indexEve($output));
        }elseif ($input->getArgument('data')==='googleForms'){
            $output->writeln($this->importDataUser->indexGoogleForms($output));
        }
        $output->writeln('Users successfully generated!');
    }
}

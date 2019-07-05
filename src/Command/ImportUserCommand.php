<?php
namespace App\Command;

use App\Entity\User;
use App\Service\ImportDataUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ImportUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:users:eve';

    public function __construct(string $name = null, ImportDataUser $importDataUser)
    {
        parent::__construct($name);
        $this->importDataUser = $importDataUser;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import users from eve')
            ->setHelp('This command allows you to import users from file xlxs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Users Creator from EVE',
            '=====================================',
            '',
        ]);

        $output->writeln($this->importDataUser->indexEve());
        $output->writeln('Users successfully generated!');
    }
}

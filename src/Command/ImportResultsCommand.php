<?php
namespace App\Command;

use App\Entity\Area;
use App\Entity\Direction;
use App\Entity\Entity;
use App\Entity\User;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use App\Service\ImportDataResult;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportResultsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:results';

    public function __construct(
        string $name = null,
        ImportDataResult $importDataResult,
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {
        parent::__construct($name);
        $this->importDataResult = $importDataResult;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import results and corrective action from file googleForms')
            ->setHelp('This command allows you to import results and corrective action Nucléaire from file xlxs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>==========================================================================================================</info>',
            '<info>========================= Results and corrective action Creator from googleForms =========================</info>',
            '<info>==========================================================================================================</info>',
            '',
        ]);
        $output->writeln($this->importDataResult->indexGoogleForms($output));
    }
}

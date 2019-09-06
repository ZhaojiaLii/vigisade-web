<?php
namespace App\Command;

use App\Entity\Area;
use App\Entity\Direction;
use App\Entity\Entity;
use App\Entity\User;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use App\Service\ImportDataEveResults;
use App\Service\ImportDataUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportEveResultsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:eve-results';

    private $importDataEveResult;

    /**
     * ImportSurveyCommand constructor.
     * @param string|null $name
     * @param ImportDataEveResults $importDataEveResult
     * @param EntityManagerInterface $em
     */
    public function __construct(
        string $name = null,
        ImportDataEveResults $importDataEveResult,
        EntityManagerInterface $em
    )
    {
        parent::__construct($name);
        $this->importDataEveResult = $importDataEveResult;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import results from file eve')
            ->setHelp('This command allows you to import results from eve tables')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>==========================================================================================================</info>',
            '<info>================================= Result Creator from EVE ================================================</info>',
            '<info>==========================================================================================================</info>',
            '',
        ]);

        $this->importDataEveResult->indexEve($output);
    }
}

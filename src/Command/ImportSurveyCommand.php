<?php
namespace App\Command;

use App\Entity\Area;
use App\Entity\Direction;
use App\Entity\Entity;
use App\Entity\User;
use App\Exception\Http\NotFoundException;
use App\Repository\UserRepository;
use App\Service\ImportDataSurvey;
use App\Service\ImportDataUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportSurveyCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    // the name of the command (the part after "bin/console")
    protected static $commandName = 'import:survey';

    private $importDataSurvey;

    /**
     * ImportSurveyCommand constructor.
     * @param string|null $name
     * @param ImportDataSurvey $importDataSurvey
     * @param EntityManagerInterface $em
     */
    public function __construct(
        string $name = null,
        ImportDataSurvey $importDataSurvey,
        EntityManagerInterface $em
    )
    {
        parent::__construct($name);
        $this->importDataSurvey = $importDataSurvey;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Import survey from file eve')
            ->setHelp('This command allows you to import survey from eve table')
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
            '<info>================================= Survey Creator from EVE ================================================</info>',
            '<info>==========================================================================================================</info>',
            '',
        ]);

        $this->importDataSurvey->indexEve($output);
    }
}

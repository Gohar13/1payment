<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\IntervalRepositoryException;
use App\Factory\IntervalFactory;
use App\Repository\IntervalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'interval:insert',
    description: 'Insert sump intervals',
    hidden: false
)]
class InsertDumpDataCommand extends Command
{
    protected static $defaultName = 'interval:insert';

    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    private IntervalRepository  $intervalRepository;
    private IntervalFactory     $intervalFactory;

    public function __construct(
        LoggerInterface         $logger,
        EntityManagerInterface  $em,
        IntervalRepository      $intervalRepository,
        IntervalFactory         $intervalFactory
    )
    {
        parent::__construct(self::$defaultName);

        $this->logger               = $logger;
        $this->em                   = $em;
        $this->intervalRepository   = $intervalRepository;
        $this->intervalFactory      = $intervalFactory;
    }

    protected function configure()
    {
        $this
            ->setDescription('Insert sump intervals')
            ->addArgument(
                'dumpFilePath',
                InputArgument::REQUIRED,
                'The path of dump file'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        try {

            $filePath = (string)$input->getArgument('dumpFilePath');

            if (!file_exists($filePath) || filesize($filePath) === 0) {
                $io->error('Invalid file path');
            }

            $this->parseCSV($filePath);

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return 1;
        }

        $io->success('Dump data has imported successfully!');

        return 0;

    }

    protected function interact(
        InputInterface  $input,
        OutputInterface $output
    ) {

        if ($input->getArgument('dumpFilePath') === null) {

            $helper = $this->getHelper('question');

            $question = new Question('Please enter the path of dump file: ', 'public/test_data.csv');

            $filePath = $helper->ask($input, $output, $question);

            $input
                ->setArgument(
                    'dumpFilePath',
                    $filePath
                );

            $output
                ->writeln(
                    sprintf(
                        'File path "%s" is  used.',
                        $filePath
                    )
                );
        }
    }

    /**
     * @throws IntervalRepositoryException
     */
    private function parseCSV(string $fileName)
    {
        $line = 0;
        $intervals = [];

        if (($handle = fopen($fileName, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $line++;
                if ($line == 1) {
                    continue;
                }

                $intervals[] = $this->intervalFactory->create((int)$data[1], (int)$data[2]);
            }

            fclose($handle);
            $this->intervalRepository->saveMultiple($intervals);
        }
    }

}
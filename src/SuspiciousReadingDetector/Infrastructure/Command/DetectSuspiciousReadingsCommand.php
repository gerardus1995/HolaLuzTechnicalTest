<?php

namespace App\SuspiciousReadingDetector\Infrastructure\Command;

use App\Shared\Infrastructure\Exception\ErrorOpeningFileException;
use App\Shared\Infrastructure\Exception\FileNotFoundException;
use App\Shared\Infrastructure\Input\Reader\AbstractReader;
use App\Shared\Infrastructure\Input\Reader\CsvReader;
use App\Shared\Infrastructure\Input\Reader\XmlReader;
use App\SuspiciousReadingDetector\Application\Detect\DTO\RequestSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Application\Detect\SuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Domain\SuspiciousReading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DetectSuspiciousReadingsCommand extends Command
{
    protected static $defaultName = 'app:detect:suspicious-readings';

    public function __construct(
        private readonly SuspiciousReadingDetector $detector
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Detects suspicious electricity readings from a given file.')
            ->addArgument('file', InputArgument::REQUIRED, 'The input file (CSV or XML).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = __DIR__ . '/../../../../data/' . $input->getArgument('file');
        $reader = $this->getReader($file);

        if (!$reader) {
            $output->writeln('<error>Unsupported file format. Use CSV or XML.</error>');

            return Command::FAILURE;
        }

        try {
            $clients = $reader->read();
        } catch (FileNotFoundException | ErrorOpeningFileException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }

        $response = $this->detector->__invoke(new RequestSuspiciousReadingDetector($clients));
        $suspiciousReadings = $response->suspiciousReadings();

        if ($suspiciousReadings->isEmpty()) {
            $output->writeln('<info>No suspicious readings detected.</info>');

            return Command::SUCCESS;
        }

        $this->generateTable($output, $suspiciousReadings);

        return Command::SUCCESS;
    }

    private function getReader(string $file): ?AbstractReader
    {
        /** @var AbstractReader|null $reader */
        $reader = match (pathinfo($file, PATHINFO_EXTENSION)) {
            'csv' => new CsvReader($file),
            'xml' => new XmlReader($file),
            default => null
        };

        return $reader;
    }

    private function generateTable(OutputInterface $output, SuspiciousReadings $suspiciousReadings): void
    {
        $table = new Table($output);
        $table->setHeaders(['Client', 'Month', 'Suspicious', 'Median']);

        /** @var SuspiciousReading $reading */
        foreach ($suspiciousReadings as $reading) {
            $table->addRow([
                $reading->getClientId(),
                $reading->getMonth(),
                $reading->getReadingValue(),
                $reading->getMedian(),
            ]);
        }

        $table->render();
    }
}

<?php

namespace App\Tests\SuspiciousReadingDetector\Infrastructure\Command;

use App\Shared\Infrastructure\Exception\FileNotFoundException;
use App\Shared\Infrastructure\Input\Reader\AbstractReader;
use App\SuspiciousReadingDetector\Application\Detect\DTO\ResponseSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Application\Detect\SuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Domain\Clients;
use App\SuspiciousReadingDetector\Domain\SuspiciousReading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;
use App\SuspiciousReadingDetector\Infrastructure\Command\DetectSuspiciousReadingsCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class DetectSuspiciousReadingsCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private MockObject|SuspiciousReadingDetector $detector;
    private MockObject|AbstractReader|null $mockReader;

    protected function setUp(): void
    {
        $this->detector = $this->createMock(SuspiciousReadingDetector::class);
        $this->mockReader = $this->createMock(AbstractReader::class);

        $command = new class ($this->detector, $this->mockReader) extends DetectSuspiciousReadingsCommand {
            private AbstractReader $mockReader;

            public function __construct(SuspiciousReadingDetector $detector, AbstractReader $mockReader)
            {
                $this->mockReader = $mockReader;
                parent::__construct($detector);
            }

            protected function getReader(string $file): ?AbstractReader
            {
                return $this->mockReader;
            }
        };

        $application = new Application();
        $application->add($command);
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteFileNotFound(): void
    {
        $this->mockReader
            ->method('read')
            ->willThrowException(new FileNotFoundException('File not found'));

        $this->commandTester->execute(['file' => 'missing.csv']);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('File not found', $output);
        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }

    public function testExecuteNoSuspiciousReadings(): void
    {
        $this->mockReader->method('read')->willReturn(new Clients());

        $this->detector->method('__invoke')->willReturn(
            new ResponseSuspiciousReadingDetector(new SuspiciousReadings())
        );

        $this->commandTester->execute(['file' => 'valid.csv']);

        $this->assertStringContainsString('No suspicious readings detected.', $this->commandTester->getDisplay());
        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    public function testExecuteWithSuspiciousReadings(): void
    {
        $readingMock = $this->createMock(SuspiciousReading::class);
        $readingMock->method('getClientId')->willReturn('123');
        $readingMock->method('getMonth')->willReturn('02');
        $readingMock->method('getReadingValue')->willReturn(150.0);
        $readingMock->method('getMedian')->willReturn(100.0);

        $suspiciousReadings = new SuspiciousReadings();
        $suspiciousReadings[] = $readingMock;

        $clients = $this->createMock(Clients::class);

        $this->mockReader->method('read')->willReturn($clients);

        $this->detector->method('__invoke')->willReturn(
            new ResponseSuspiciousReadingDetector($suspiciousReadings)
        );

        $this->commandTester->execute(['file' => 'valid.csv']);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('123', $output);
        $this->assertStringContainsString('02', $output);
        $this->assertStringContainsString('150', $output);
        $this->assertStringContainsString('100', $output);

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }
}
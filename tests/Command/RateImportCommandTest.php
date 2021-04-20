<?php


namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\RateImport;
use App\Service\Import\RateImportService;

class RateImportCommandTest extends KernelTestCase
{
    /** @var RateImportService */
    private $rateImportServiceMock;
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $this->rateImportServiceMock = $this->getMockBuilder(RateImportService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $application->add(new RateImport($this->rateImportServiceMock));
        $command = $application->find('rate:import');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        $this->rateImportServiceMock = null;
        $this->commandTester = null;
    }

    public function testPositiveImportExecute()
    {
        $this->rateImportServiceMock->expects($this->once())
            ->method('import')
            ->will($this->returnValue(true));

        $this->commandTester->execute([]);
        $this->assertStringContainsString('Import rates done!', $this->commandTester->getDisplay());

    }

    public function testNegativeImportExecute()
    {
        $this->rateImportServiceMock->expects($this->once())
            ->method('import')
            ->will($this->returnValue(false));

        $this->commandTester->execute([]);
        $this->assertStringContainsString('Import rates failed with errors', $this->commandTester->getDisplay());

    }
}
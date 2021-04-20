<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Import\RateImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RateImport extends Command
{
    public RateImportService $rateImportService;

    public function __construct(
        RateImportService $rateImportService,
        ?string $name = null
    )
    {
        parent::__construct($name);
        $this->rateImportService = $rateImportService;
    }

    protected function configure(): void
    {
        $this->setName('rate:import');
        $this->setDescription('Import all rates');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        if ($this->rateImportService->import()) {
            $output->writeln('Import rates done!');
            return Command::SUCCESS;
        } else {
            $output->writeln('Import rates failed with errors. Check error log for more information');
            return Command::FAILURE;
        }
    }
}

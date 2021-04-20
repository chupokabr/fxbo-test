<?php


namespace App\Service\Import;


use App\Repository\RateRepository;
use App\Service\SourceData\SourceDataInterface;
use Psr\Log\LoggerInterface;

class RateImportService
{
    private LoggerInterface $logger;
    private RateRepository $rateRepository;
    private SourceDataInterface $sourceData;

    public function __construct(
        LoggerInterface $logger,
        RateRepository $rateRepository,
        SourceDataInterface $sourceData
    )
    {
        $this->logger = $logger;
        $this->rateRepository = $rateRepository;
        $this->sourceData = $sourceData;
    }

    public function import(): bool
    {
        try {
            $rates = [];
            foreach ($this->sourceData->process() as $rate) {
                $rates[] = $rate;
            }

            $this->rateRepository->batchUpsert($rates);
            $this->logger->info(sprintf('Imported %d rates', count($rates)));

            return true;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to import rates: ');
            $this->logger->error($exception->getMessage());

            return false;
        }
    }
}
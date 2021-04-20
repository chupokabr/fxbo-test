<?php

declare(strict_types=1);

namespace App\Service\SourceData;

use Traversable;

final class AllActiveSourceData implements SourceDataInterface
{
    /** @var SourceDataInterface[] */
    private $dataSources = [];

    /**
     * AllActiveSourceData constructor.
     * @param SourceDataInterface[] $dataSources
     */
    public function __construct(
        array $dataSources = []
    )
    {
        $this->dataSources = $dataSources;
    }

    public function process(): Traversable
    {
        foreach ($this->dataSources as $source) {
            yield from $source->process();
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Service\SourceData;

use Traversable;

interface SourceDataInterface
{

    /**
     * @return Traversable [
     *     'base' => (string) Base currency,
     *     'quote' => (string) Quote currency,
     *     'price' => (string) Price,
     *     'provider' => (string) Provider,
     * ];
     */
    public function process(): Traversable;
}

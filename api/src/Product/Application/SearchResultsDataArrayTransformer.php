<?php

declare(strict_types=1);

namespace Dominio\Product\Application;

use Dominio\Product\Domain\SearchResult;

class SearchResultsDataArrayTransformer
{
    /** @var SearchResult[] */
    private array $results;

    /** @param SearchResult[] $results */
    private function __construct(array $results)
    {
        $this->results = $results;
    }

    /** @param SearchResult[] $results */
    public static function write(array $results): self
    {
        return new self($results);
    }

    /** @return array<mixed> */
    public function read(): array
    {
        $data = [];

        foreach ($this->results as $result) {
            $data[] = $result->toArray();
        }

        return $data;
    }
}

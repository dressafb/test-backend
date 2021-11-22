<?php

declare(strict_types=1);

namespace Dominio\Product\Domain;

interface SearchClient
{
    /** @return SearchResult[] */
    public function getProducts(string $orderBy, int $fromPage, int $toPage, string $category): array;
}

<?php

declare(strict_types=1);

namespace Dominio\Product\Domain;

class SearchResult
{
    private string $id;
    private string $escapedName;
    private string $descriptionShort;
    private string $productLink;
    private string $uri;
    private string $brandName;
    private string $brandId;
    private float $listPrice;
    private float $bestPrice;
    private bool $hasBestPrice;
    private int $numbersOfInstallment;
    private float $installmentValue;
    private float $listPriceMinusBestPrice;
    private bool $isInStock;

    public function __construct(
        string $id,
        string $escapedName,
        string $descriptionShort,
        string $productLink,
        string $uri,
        string $brandName,
        string $brandId,
        float $listPrice,
        float $bestPrice,
        bool $hasBestPrice,
        int $numbersOfInstallment,
        float $installmentValue,
        float $listPriceMinusBestPrice,
        bool $isInStock
    ) {
        $this->id = $id;
        $this->escapedName = $escapedName;
        $this->descriptionShort = $descriptionShort;
        $this->productLink = $productLink;
        $this->uri = $uri;
        $this->brandName = $brandName;
        $this->brandId = $brandId;
        $this->listPrice = $listPrice;
        $this->bestPrice = $bestPrice;
        $this->hasBestPrice = $hasBestPrice;
        $this->numbersOfInstallment = $numbersOfInstallment;
        $this->installmentValue = $installmentValue;
        $this->listPriceMinusBestPrice = $listPriceMinusBestPrice;
        $this->isInStock = $isInStock;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function escapedName(): string
    {
        return $this->escapedName;
    }

    public function descriptionShort(): string
    {
        return $this->descriptionShort;
    }

    public function productLink(): string
    {
        return $this->productLink;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function brandName(): string
    {
        return $this->brandName;
    }

    public function brandId(): string
    {
        return $this->brandId;
    }

    public function listPrice(): float
    {
        return $this->listPrice;
    }

    public function bestPrice(): float
    {
        return $this->bestPrice;
    }

    public function hasBestPrice(): bool
    {
        return $this->hasBestPrice;
    }

    public function numbersOfInstallment(): int
    {
        return $this->numbersOfInstallment;
    }

    public function installmentValue(): float
    {
        return $this->installmentValue;
    }

    public function listPriceMinusBestPrice(): float
    {
        return $this->listPriceMinusBestPrice;
    }

    public function isInStock(): bool
    {
        return $this->isInStock;
    }

    /** @return array<mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'escapedName' => $this->escapedName,
            'descriptionShort' => $this->descriptionShort,
            'productLink' => $this->productLink,
            'uri' => $this->uri,
            'brandName' => $this->brandName,
            'brandId' => $this->brandId,
            'listPrice' => $this->listPrice,
            'bestPrice' => $this->bestPrice,
            'hasBestPrice' => $this->hasBestPrice,
            'numbersOfInstallment' => $this->numbersOfInstallment,
            'installmentValue' => $this->installmentValue,
            'listPriceMinusBestPrice' => $this->listPriceMinusBestPrice,
            'isInStock' => $this->isInStock,
        ];
    }
}

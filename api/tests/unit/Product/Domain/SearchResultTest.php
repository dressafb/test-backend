<?php

declare(strict_types=1);

namespace Tests\Product\Domain;

use Dominio\Product\Domain\SearchResult;
use PHPUnit\Framework\TestCase;

class SearchResultTest extends TestCase
{
    /** @test */
    public function create_search_result(): void
    {
        $result = new SearchResult(
            '4196',
            'La Vie Est Belle Lancôme',
            'Perfume La Vie Est Belle Lancôme',
            'https://www.epocacosmeticos.com.br/path/p',
            'http://path.jpg?v=636759764304030000',
            'Lancôme',
            '2000000',
            329,
            259,
            true,
            8,
            32.37,
            70,
            true
        );

        $this->assertInstanceOf(SearchResult::class, $result);
        $this->assertEquals('4196', $result->id());
        $this->assertEquals('La Vie Est Belle Lancôme', $result->escapedName());
        $this->assertEquals('Perfume La Vie Est Belle Lancôme', $result->descriptionShort());
        $this->assertEquals('https://www.epocacosmeticos.com.br/path/p', $result->productLink());
        $this->assertEquals('http://path.jpg?v=636759764304030000', $result->uri());
        $this->assertEquals('Lancôme', $result->brandName());
        $this->assertEquals('2000000', $result->brandId());
        $this->assertEquals(329.00, $result->listPrice());
        $this->assertEquals(259.00, $result->bestPrice());
        $this->assertTrue($result->hasBestPrice());
        $this->assertEquals(8, $result->numbersOfInstallment());
        $this->assertEquals(32.37, $result->installmentValue());
        $this->assertEquals(70, $result->listPriceMinusBestPrice());
        $this->assertTrue($result->isInStock());
    }

    /** @test */
    public function return_correct_serach_result_array(): void
    {
        $expected = [
            'id' => '4196',
            'escapedName' => 'La Vie Est Belle Lancôme',
            'descriptionShort' => 'Perfume La Vie Est Belle Lancôme',
            'productLink' => 'https://www.epocacosmeticos.com.br/path/p',
            'uri' => 'http://path.jpg?v=636759764304030000',
            'brandName' => 'Lancôme',
            'brandId' => '2000000',
            'listPrice' => 329.0,
            'bestPrice' => 259.0,
            'hasBestPrice' => true,
            'numbersOfInstallment' => 8,
            'installmentValue' => 32.37,
            'listPriceMinusBestPrice' => 70.0,
            'isInStock' => true,
        ];
        $result = new SearchResult(
            '4196',
            'La Vie Est Belle Lancôme',
            'Perfume La Vie Est Belle Lancôme',
            'https://www.epocacosmeticos.com.br/path/p',
            'http://path.jpg?v=636759764304030000',
            'Lancôme',
            '2000000',
            329,
            259,
            true,
            8,
            32.37,
            70,
            true
        );

        $this->assertEquals($expected, $result->toArray());
    }
}

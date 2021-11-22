<?php

declare(strict_types=1);

namespace Tests\Product\Application;

use Dominio\Product\Application\SearchResultsDataArrayTransformer;
use Dominio\Product\Domain\SearchResult;
use PHPUnit\Framework\TestCase;

class SearchResultsDataArrayTransformerTest extends TestCase
{
    /** @test */
    public function should_transform_search_results_in_array(): void
    {
        $expectedResult = [
            [
                'id' => '4196',
                'escapedName' => 'La Vie Est Belle Lancôme - Perfume Feminino - Eau de Parfum',
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
            ],
            [
                'id' => '27001',
                'escapedName' => 'Adorada Phytoderm Perfume Feminino - Deo Colônia',
                'descriptionShort' => 'Perfume Adorada Phytoderm - Feminino',
                'productLink' => 'https://www.epocacosmeticos.com.br/path/p',
                'uri' => 'http://path.jpg?v=636759764304030000',
                'brandName' => 'Phytoderm',
                'brandId' => '2000473',
                'listPrice' => 14.9,
                'bestPrice' => 9.9,
                'hasBestPrice' => true,
                'numbersOfInstallment' => 1,
                'installmentValue' => 9.9,
                'listPriceMinusBestPrice' => 5.0,
                'isInStock' => true,
            ],

        ];

        $results = [
            new SearchResult(
                '4196',
                'La Vie Est Belle Lancôme - Perfume Feminino - Eau de Parfum',
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
            ),
            new SearchResult(
                '27001',
                'Adorada Phytoderm Perfume Feminino - Deo Colônia',
                'Perfume Adorada Phytoderm - Feminino',
                'https://www.epocacosmeticos.com.br/path/p',
                'http://path.jpg?v=636759764304030000',
                'Phytoderm',
                '2000473',
                14.9,
                9.9,
                true,
                1,
                9.9,
                5.0,
                true
            ),
        ];

        $transformer = SearchResultsDataArrayTransformer::write($results);

        $this->assertEquals($expectedResult, $transformer->read());
    }
}

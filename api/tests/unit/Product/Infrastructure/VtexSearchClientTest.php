<?php

declare(strict_types=1);

namespace Tests\Product\Infrastructure;

use Dominio\Product\Domain\Exception\BadRequest;
use Dominio\Product\Infrastructure\VtexSearchClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

class VtexSearchClientTest extends TestCase
{
    /** @test */
    public function should_return_all_messages(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $expectedResult = [
            'id' => '27001',
            'escapedName' => 'Adorada Phytoderm Perfume Feminino - Deo Colônia',
            'descriptionShort' => 'Perfume Adorada Phytoderm - Feminino',
            'productLink' => 'https://www.epocacosmeticos.com.br/adorada-phytoderm-perfume-feminino-deo-colonia/p',
            'uri' => 'http://epocacosmeticos.vteximg.com.br/arquivos/ids/283033/agua-adorada1.jpg?v=636759764304030000',
            'brandName' => 'Phytoderm',
            'brandId' => '2000473',
            'listPrice' => 14.9,
            'bestPrice' => 9.9,
            'hasBestPrice' => true,
            'numbersOfInstallment' => 1,
            'installmentValue' => 9.9,
            'listPriceMinusBestPrice' => 5.0,
            'isInStock' => true,
        ];

        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) file_get_contents('/app/tests/assets/search-result-vtex.json')
            ),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $guzzleClient = new Client(['handler' => $handlerStack]);
        $client = new VtexSearchClient($guzzleClient, 'https://www.epocacosmeticos.com.br');

        $result = $client->getProducts('OrderByTopSaleDESC', 1, 1, 'perfumes');

        $this->assertCount(1, $result);
        $this->assertEquals($expectedResult, $result[0]->toArray());
    }

    /** @test */
    public function should_throw_exception_if_request_fails(): void
    {
        $this->expectException(BadRequest::class);
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                400,
                ['Content-Type' => 'application/json'],
                ''
            ),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $guzzleClient = new Client(['handler' => $handlerStack]);
        $client = new VtexSearchClient($guzzleClient, 'https://www.epocacosmeticos.com.br');

        $client->getProducts('OrderByTopSaleDESC', 1, 1, 'perfumes');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Product\Application;

use Dominio\Product\Application\GetProducts;
use Dominio\Product\Application\GetProductsHandler;
use Dominio\Product\Application\SearchResultsDataArrayTransformer;
use Dominio\Product\Infrastructure\VtexSearchClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

class GetProductsHandlerTest extends TestCase
{
    /** @test */
    public function should_handle_product_search(): void
    {
        $container = [];
        $history = Middleware::history($container);

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
        $command = new GetProducts();
        $handler = new GetProductsHandler($client);

        $result = $handler($command);

        $this->assertInstanceOf(SearchResultsDataArrayTransformer::class, $result);
    }
}

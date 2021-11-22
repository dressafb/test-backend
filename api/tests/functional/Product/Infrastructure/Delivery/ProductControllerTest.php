<?php

declare(strict_types=1);

namespace Tests\Product\Infrastructure\Delivery;

use Tests\Framework\DoctrineTestCase;

use function json_decode;

class ProductControllerTest extends DoctrineTestCase
{
    /** @test */
    public function should_return_search_result(): void
    {
        $client = self::$client;
        $client->request('GET', '/search');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(12, json_decode((string) $response->getContent() ?? '', true));
    }
}

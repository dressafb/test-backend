<?php

declare(strict_types=1);

namespace Tests\Product\Domain\Exception;

use Dominio\Product\Domain\Exception\BadRequest;
use PHPUnit\Framework\TestCase;

use function sprintf;

class BadRequestTest extends TestCase
{
    /** @test */
    public function throw_exception_when_bad_request(): void
    {
        $client = 'vtex';
        $url = 'https://www.epocacosmeticos.com.br';
        $error = 'Error message';
        $expectedErrorMessage = sprintf('BadRequest %s in %s: %s', $client, $url, $error);
        $expectedTypeUri = $url;
        $expectedTitle = 'Bad Request';
        $expectedExtraDetails = ['client' => $client, 'url' => $url, 'error' => $error];

        $exception = BadRequest::fromClient($client, $url, $error);

        $this->assertInstanceOf(BadRequest::class, $exception);
        $this->assertEquals($expectedErrorMessage, $exception->getMessage());
        $this->assertEquals($expectedTypeUri, $exception->getTypeUri());
        $this->assertEquals($expectedTitle, $exception->getTitle());
        $this->assertEquals($expectedExtraDetails, $exception->getExtraDetails());
    }
}

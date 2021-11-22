<?php

declare(strict_types=1);

namespace Dominio\Product\Domain\Exception;

use Exception;
use Lcobucci\ErrorHandling\Problem\Detailed;
use Lcobucci\ErrorHandling\Problem\InvalidRequest;
use Lcobucci\ErrorHandling\Problem\Titled;
use Lcobucci\ErrorHandling\Problem\Typed;

use function sprintf;

final class BadRequest extends Exception implements InvalidRequest, Typed, Titled, Detailed
{
    private string $client;
    private string $url;
    private string $error;

    public static function fromClient(string $client, string $url, string $error): self
    {
        $exception = new self(sprintf('BadRequest %s in %s: %s', $client, $url, $error));
        $exception->client = $client;
        $exception->url = $url;
        $exception->error = $error;

        return $exception;
    }

    public function getTypeUri(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return 'Bad Request';
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtraDetails(): array
    {
        return [
            'client' => $this->client,
            'url' => $this->url,
            'error' => $this->error,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Dominio\Product\Application;

use Dominio\Product\Domain\SearchClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetProductsHandler implements MessageHandlerInterface
{
    private SearchClient $client;

    public function __construct(SearchClient $client)
    {
        $this->client = $client;
    }

    public function __invoke(GetProducts $command): SearchResultsDataArrayTransformer
    {
        $result = $this->client->getProducts(
            $command->orderBy,
            $command->fromPage,
            $command->toPage,
            $command->category
        );

        return SearchResultsDataArrayTransformer::write($result);
    }
}

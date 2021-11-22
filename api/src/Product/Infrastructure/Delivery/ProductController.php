<?php

declare(strict_types=1);

namespace Dominio\Product\Infrastructure\Delivery;

use Dominio\Product\Application\GetProducts;
use Dominio\Product\Application\SearchResultsDataArrayTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

use function assert;

class ProductController
{
    /** @Route("/search", methods={"GET"}, name="search_product") */
    public function search(MessageBusInterface $bus): Response
    {
        $command = new GetProducts();
        $getStatusHandler = $bus->dispatch($command);

        $handled = $getStatusHandler->last(HandledStamp::class);
        assert($handled instanceof HandledStamp);

        $response = $handled->getResult();
        assert($response instanceof SearchResultsDataArrayTransformer);

        return new JsonResponse($response->read(), Response::HTTP_OK);
    }
}

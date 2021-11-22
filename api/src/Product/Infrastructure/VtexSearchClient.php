<?php

declare(strict_types=1);

namespace Dominio\Product\Infrastructure;

use Dominio\Product\Domain\Exception\BadRequest;
use Dominio\Product\Domain\SearchClient;
use Dominio\Product\Domain\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

use function json_decode;
use function sprintf;

class VtexSearchClient implements SearchClient
{
    private Client $httpClient;
    private string $baseUrl;

    public function __construct(Client $httpClient, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    /** @return SearchResult[] */
    public function getProducts(string $orderBy, int $fromPage, int $toPage, string $category): array
    {
        $request = new Request(
            'GET',
            sprintf(
                '%s/api/catalog_system/pub/products/search/%s?_from=%d&_to=%d&O=%s',
                $this->baseUrl,
                $category,
                $fromPage,
                $toPage,
                $orderBy
            ),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        );

        $response = $this->makeRequest($request);

        return $this->formatResponse($response);
    }

    private function makeRequest(Request $request): ResponseInterface
    {
        try {
            return $this->httpClient->send($request);
        } catch (TransferException $error) {
            throw BadRequest::fromClient('vtex', $request->getUri()->getPath(), $error->getMessage());
        }
    }

    /** @return SearchResult[] */
    private function formatResponse(ResponseInterface $response): array
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);
        $searcResult = [];

        foreach ($responseBody as $product) {
            $bestPriceProduct = $this->findItemWithBestPrice($product['items']);
            $bestPrice = $bestPriceProduct['sellers'][0]['commertialOffer']['Price'];
            $listPrice = $bestPriceProduct['sellers'][0]['commertialOffer']['ListPrice'];
            $installments = $bestPriceProduct['sellers'][0]['commertialOffer']['Installments'];
            $bestInstallmentsOption = $this->findMaxNumberOfInstallments($installments);

            $searcResult[] = new SearchResult(
                $product['productId'],
                $product['productName'],
                $product['productTitle'],
                $product['link'],
                $bestPriceProduct['images'][0]['imageUrl'],
                $product['brand'],
                (string) $product['brandId'],
                $listPrice,
                $bestPrice,
                $bestPrice < $listPrice,
                $bestInstallmentsOption['NumberOfInstallments'],
                $bestInstallmentsOption['Value'],
                $listPrice - $bestPrice,
                (bool) $bestPriceProduct['sellers'][0]['commertialOffer']['IsAvailable']
            );
        }

        return $searcResult;
    }

    /**
     * @param array<string, mixed> $items
     *
     * @return array<string, mixed>
     */
    private function findItemWithBestPrice(array $items): array
    {
        $bestPrice = 999999;
        $itemIndex = 0;

        foreach ($items as $key => $item) {
            if ($bestPrice <= $item['sellers'][0]['commertialOffer']['Price']) {
                continue;
            }

            $bestPrice = $item['sellers'][0]['commertialOffer']['Price'];
            $itemIndex = $key;
        }

        return $items[$itemIndex];
    }

    /**
     * @param array<string, mixed> $installments
     *
     * @return array<string, mixed>
     */
    private function findMaxNumberOfInstallments(array $installments): array
    {
        $numberOfInstallments = 0;
        $installmentsIndex = 0;

        foreach ($installments as $key => $item) {
            if ($numberOfInstallments >= $item['NumberOfInstallments']) {
                continue;
            }

            $numberOfInstallments = $item['NumberOfInstallments'];
            $installmentsIndex = $key;
        }

        return $installments[$installmentsIndex];
    }
}

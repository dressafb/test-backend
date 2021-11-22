<?php

declare(strict_types=1);

namespace Dominio\Product\Application;

use Symfony\Component\Validator\Constraints as Assert;

/** @psalm-immutable */
final class GetProducts
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    public string $category;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    public int $toPage;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    public int $fromPage;

    /** @Assert\Choice({"OrderByPriceDESC", "OrderByPriceASC", "OrderByTopSaleDESC", "OrderByReviewRateDESC", "OrderByNameASC", "OrderByNameDESC", "OrderByReleaseDateDESC", "OrderByBestDiscountDESC", "OrderByScoreDESC"}) */
    public string $orderBy;

    public function __construct(
        string $orderBy = 'OrderByTopSaleDESC',
        int $fromPage = 1,
        int $toPage = 12,
        string $category = 'perfumes'
    ) {
        $this->orderBy = $orderBy;
        $this->fromPage = $fromPage;
        $this->toPage = $toPage;
        $this->category = $category;
    }
}

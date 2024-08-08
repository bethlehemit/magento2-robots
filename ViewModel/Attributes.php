<?php declare(strict_types=1);

namespace BethlehemIT\Robots\ViewModel;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Attributes implements ArgumentInterface
{
    /**
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        protected readonly ProductAttributeRepositoryInterface $productAttributeRepository,
        protected readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Returns an array of attribute codes that are filterable
     *
     * @return array[string]
     */
    public function getAttributes(): array
    {
        $attributeCodes = [];
        foreach ($this->productAttributeRepository->getList($this->getSearchCriteria())->getItems() as $attribute) {
            $attributeCodes[] = $attribute->getAttributeCode();
        }

        sort($attributeCodes);
        return $attributeCodes;
    }

    /**
     * Generate search criteria
     *
     * @return SearchCriteria
     */
    private function getSearchCriteria()
    {
        return $this->searchCriteriaBuilder
            ->addFilter('additional_table.is_filterable', 1)
            ->create();
    }
}

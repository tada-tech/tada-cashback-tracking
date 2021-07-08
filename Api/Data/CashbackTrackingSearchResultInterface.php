<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CashbackTrackingSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get CashbackTracking list
     *
     * @return CashbackTrackingInterface[]
     */
    public function getItems();

    /**
     * Set CashbackTracking list
     *
     * @param CashbackTrackingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

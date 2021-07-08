<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;
use Tada\CashbackTracking\Api\Data\CashbackTrackingSearchResultInterface;
use Tada\CashbackTracking\Model\CashbackTracking;

interface CashbackTrackingRepositoryInterface
{
    /**
     * @param CashbackTrackingInterface $object
     * @return CashbackTrackingInterface
     */
    public function save(CashbackTrackingInterface $object);

    /**
     * @param int $entityId
     * @param bool $forceReload
     * @return CashbackTrackingInterface|CashbackTracking
     * @throws NoSuchEntityException
     */
    public function get(int $entityId, bool $forceReload = false);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return CashbackTrackingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CashbackTrackingInterface $object
     * @return CashbackTrackingInterface
     * @throws \Exception
     */
    public function delete(CashbackTrackingInterface $object): CashbackTrackingInterface;
}

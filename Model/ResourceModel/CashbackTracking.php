<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;

class CashbackTracking extends AbstractDb
{
    /**
     * @inheridoc
     */
    protected function _construct()
    {
        $this->_init(
            CashbackTrackingInterface::TBL_NAME,
            CashbackTrackingInterface::ENTITY_ID
        );
    }

    /**
     * @param int $orderId
     * @return int|false
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdByOrderId(int $orderId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), CashbackTrackingInterface::ENTITY_ID)
            ->where('order_id = :order_id');

        $bind = [':order_id' => $orderId];

        return $connection->fetchOne($select, $bind);
    }
}

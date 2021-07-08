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
}

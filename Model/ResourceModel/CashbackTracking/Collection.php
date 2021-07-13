<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Model\ResourceModel\CashbackTracking;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tada\CashbackTracking\Model\ResourceModel\CashbackTracking as ResourceModel;
use Tada\CashbackTracking\Model\CashbackTracking as Model;

class Collection extends AbstractCollection
{
    /**
     *  Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Model::class,
            ResourceModel::class
        );
    }
}

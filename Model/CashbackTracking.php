<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;
use Tada\CashbackTracking\Model\ResourceModel\CashbackTracking as ResourceModel;
use Tada\CashbackTracking\Api\Data\CashbackTrackingExtensionInterface;

class CashbackTracking extends AbstractExtensibleModel implements CashbackTrackingInterface
{

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'cashback_tracking';

    /**
     * @inheridoc
     */
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int|mixed|null
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $value
     * @return CashbackTracking|void
     */
    public function setEntityId($value)
    {
        $this->setData(self::ENTITY_ID, $value);
    }

    /**
     * @return mixed|string|null
     */
    public function getAttributeOne()
    {
        return $this->getData(self::ATTRIBUTE_ONE);
    }

    /**
     * @param string $value
     * @return CashbackTracking|void
     */
    public function setAttributeOne(string $value)
    {
        $this->setData(self::ATTRIBUTE_ONE, $value);
    }

    /**
     * @return float|mixed|null
     */
    public function getAttributeTwo()
    {
        return $this->getData(self::ATTRIBUTE_TWO);
    }

    /**
     * @param float $value
     * @return CashbackTracking|void
     */
    public function setAttributeTwo(float $value)
    {
        $this->setData(self::ATTRIBUTE_TWO, $value);
    }

    /**
     * @return int|mixed|null
     */
    public function getAttributeThree()
    {
        return $this->getData(self::ATTRIBUTE_THREE);
    }

    /**
     * @param int $value
     * @return CashbackTracking|void
     */
    public function setAttributeThree(int $value)
    {
        $this->setData(self::ATTRIBUTE_THREE, $value);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Tada\CashbackTracking\Api\Data\CashbackTrackingExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Tada\CashbackTracking\Api\Data\CashbackTrackingExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(CashbackTrackingExtensionInterface $extensionAttributes)
    {
        return $this->setExtensionAttributes($extensionAttributes);
    }
}

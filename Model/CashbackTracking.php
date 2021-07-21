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
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId)
    {
        $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @return string
     */
    public function getPartner(): string
    {
        return $this->getData(self::PARTNER);
    }

    /**
     * @param string $partner
     * @return $this
     */
    public function setPartner(string $partner)
    {
        $this->setData(self::PARTNER, $partner);
    }

    /**
     * @return string
     */
    public function getPartnerParameter(): string
    {
        return $this->getData(self::PARTNER_PARAMETER);
    }

    /**
     * @param string $partnerParameter
     * @return $this
     */
    public function setPartnerParameter(string $partnerParameter)
    {
        $this->setData(self::PARTNER_PARAMETER, $partnerParameter);
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
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}

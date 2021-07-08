<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CashbackTrackingInterface extends ExtensibleDataInterface
{
    const TBL_NAME = 'tada_cashback_tracking';
    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const PARTNER = 'partner';
    const PARTNER_PARAMETER = 'partner_parameter';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $value
     * @return $this
     */
    public function setEntityId($value);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId);

    /**
     * @return string
     */
    public function getPartner():string;

    /**
     * @param string $partner
     * @return $this
     */
    public function setPartner(string $partner);

    /**
     * @return string
     */
    public function getPartnerParameter():string;

    /**
     * @param string $partnerParameter
     * @return $this
     */
    public function setPartnerParameter(string $partnerParameter);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Tada\CashbackTracking\Api\Data\CashbackTrackingExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Tada\CashbackTracking\Api\Data\CashbackTrackingExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(CashbackTrackingExtensionInterface $extensionAttributes);
}

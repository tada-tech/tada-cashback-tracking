<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CashbackTrackingInterface extends ExtensibleDataInterface
{
    const TBL_NAME = 'tada_cashback_tracking';
    const ENTITY_ID = 'entity_id';
    const ATTRIBUTE_ONE = 'attribute_one';
    const ATTRIBUTE_TWO = 'attribute_two';
    const ATTRIBUTE_THREE = 'attribute_three';

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
     * @return string
     */
    public function getAttributeOne();

    /**
     * @param string $value
     * @return $this
     */
    public function setAttributeOne(string $value);

    /**
     * @return float
     */
    public function getAttributeTwo();

    /**
     * @param float $value
     * @return $this
     */
    public function setAttributeTwo(float $value);

    /**
     * @return int
     */
    public function getAttributeThree();

    /**
     * @param int $value
     * @return $this
     */
    public function setAttributeThree(int $value);

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

<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Model\Plugin\OrderRepository;

use Magento\Framework\Exception\NoSuchEntityException;

class GetPartnerTrackingPlugin
{
    /**
     * @var \Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface
     */
    protected $cashbackTrackingRepository;

    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * GetPartnerTrackingPlugin constructor.
     * @param \Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface $cashbackTrackingRepository
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        \Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface $cashbackTrackingRepository,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->cashbackTrackingRepository = $cashbackTrackingRepository;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderInterface $resultOrder
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $resultOrder
    ) {
        $resultOrder = $this->getPartnerTracking($resultOrder);

        return $resultOrder;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getPartnerTracking(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        try {
            $partnerTracking = $this->cashbackTrackingRepository->getByOrderId((int) $order->getEntityId());
        } catch (NoSuchEntityException $e) {
            return $order;
        }

        $extensionAttributes = $order->getExtensionAttributes();
        /** @var \Magento\Sales\Api\Data\OrderExtension $orderExtension */
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();
        $orderExtension->setPartnerTracking($partnerTracking);
        $order->setExtensionAttributes($orderExtension);

        return $order;
    }
}

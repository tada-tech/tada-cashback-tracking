<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;
use Tada\CashbackTracking\Model\CashbackTracking;
use Tada\CashbackTracking\Model\CashbackTrackingFactory;

/**
 * Class SaveOrderAfterSubmitObserver
 */
class SaveCashbackTrackingEntityObserver implements ObserverInterface
{
    /**
     * @var CashbackTrackingFactory
     */
    protected $cashbackTrackingFactory;

    /**
     * @var CashbackTrackingRepositoryInterface
     */
    protected $cashbackTrackingRepository;

    /**
     * SaveCashbackTrackingEntityObserver constructor.
     * @param CashbackTrackingFactory $cashbackTrackingFactory
     * @param CashbackTrackingRepositoryInterface $cashbackTrackingRepository
     */
    public function __construct(
        CashbackTrackingFactory $cashbackTrackingFactory,
        CashbackTrackingRepositoryInterface $cashbackTrackingRepository
    ) {
        $this->cashbackTrackingFactory = $cashbackTrackingFactory;
        $this->cashbackTrackingRepository = $cashbackTrackingRepository;
    }

    /**
     * Save order into registry to use it in the overloaded controller.
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getData('order');
        $AdditionalInformation = $order->getPayment()->getAdditionalInformation();

        //Save CashbackTrackingEntity
        /** @var CashbackTracking $exitedCashbackEntity */
        $exitedCashbackEntity = $this->cashbackTrackingRepository->getByOrderId((int)$order->getEntityId());

        /** @var CashbackTracking $cashbackEntity */
        $cashbackEntity = $exitedCashbackEntity ?: $this->cashbackTrackingFactory->create();

        if (!$exitedCashbackEntity) {
            $cashbackEntity->setOrderId((int)$order->getEntityId());
        }

        $cashbackEntity->setPartner($AdditionalInformation[CashbackTrackingInterface::PARTNER]);
        $cashbackEntity->setPartnerParameter($AdditionalInformation[CashbackTrackingInterface::PARTNER_PARAMETER]);
        $this->cashbackTrackingRepository->save($cashbackEntity);

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Test\Integration;

use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Tada\CashbackTracking\Model\CashbackTrackingFactory;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Sales\Api\OrderRepositoryInterface;

class CashbackTrackingRepositoryTest extends TestCase
{

    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var CashbackTrackingRepositoryInterface
     */
    protected $cashbackTrackingRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CashbackTrackingFactory
     */
    protected $CashbackTrackingFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->cashbackTrackingRepository = $this->objectManager->get(CashbackTrackingRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->objectManager->get(SearchCriteriaBuilder::class);
        $this->cashbackTrackingFactory = $this->objectManager->get(CashbackTrackingFactory::class);
        $this->cartManagement = $this->objectManager->create(CartManagementInterface::class);
        $this->orderRepository = $this->objectManager->get(OrderRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/quote.php
     * @magentoAppIsolation enabled
     */
    public function testGetCashbackTrackingEntity()
    {
        $quote = $this->_getQuote('test01');
        $quote = $this->_prepareQuote($quote);

        $orderId = $this->cartManagement->placeOrder($quote->getId());

        $cashbackTrackingEntity = $this->cashbackTrackingRepository->getByOrderId((int) $orderId);

        $this->assertEquals('shopback', $cashbackTrackingEntity->getPartner());
        $this->assertEquals('happy10discount', $cashbackTrackingEntity->getPartnerParameter());
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/quote.php
     * @magentoAppIsolation enabled
     */
    public function testGetOrderWithPartnerTrackingExtensionAttribute()
    {
        $quote = $this->_getQuote('test01');
        $quote = $this->_prepareQuote($quote);

        $orderId = $this->cartManagement->placeOrder($quote->getId());

        $order = $this->orderRepository->get((int)$orderId);
        $orderPartnerTracking = $order->getExtensionAttributes()->getPartnerTracking();
        $cashbackTrackingEntity = $this->cashbackTrackingRepository->getByOrderId((int) $orderId);

        $this->assertEquals($cashbackTrackingEntity, $orderPartnerTracking);
    }

    /**
     * Gets quote by reserved order ID.
     *
     * @param string $reservedOrderId
     * @return Quote
     */
    private function _getQuote(string $reservedOrderId): Quote
    {
        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->objectManager->get(SearchCriteriaBuilder::class);
        $searchCriteria = $searchCriteriaBuilder->addFilter('reserved_order_id', $reservedOrderId)
            ->create();

        /** @var CartRepositoryInterface $quoteRepository */
        $quoteRepository = $this->objectManager->get(CartRepositoryInterface::class);
        $items = $quoteRepository->getList($searchCriteria)
            ->getItems();

        return array_pop($items);
    }

    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return Quote
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function _prepareQuote(\Magento\Quote\Api\Data\CartInterface $quote): Quote
    {
        $tracking = [
            'partner' => 'shopback',
            'partner_parameter' => 'happy10discount'
        ];

        $quote->getPayment()->setAdditionalInformation($tracking);

        $shippingAddress = $quote->getShippingAddress();

        /** @var $rate Rate */
        $rate = $this->objectManager->create(Rate::class);
        $rate->setCode('flatrate_flatrate');
        $rate->setPrice(5);

        $shippingAddress->setShippingMethod('flatrate_flatrate');
        $shippingAddress->addShippingRate($rate);

        $quote->setShippingAddress($shippingAddress);
        $quote->setCheckoutMethod('guest');
        $quoteRepository = $this->objectManager
            ->get(\Magento\Quote\Api\CartRepositoryInterface::class);
        $quoteRepository->save($quote);

        return $quote;
    }
}

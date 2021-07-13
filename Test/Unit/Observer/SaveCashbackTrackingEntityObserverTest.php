<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Test\Unit\Observer;

use Magento\Framework\Event;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;
use Mockery;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;
use Tada\CashbackTracking\Model\CashbackTracking;
use Tada\CashbackTracking\Model\CashbackTrackingFactory;
use Tada\CashbackTracking\Observer\SaveCashbackTrackingEntityObserver;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;

class SaveCashbackTrackingEntityObserverTest extends TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $cashbackTrackingFactory;

    /**
     * @var Mockery\MockInterface
     */
    protected $cashbackTrackingRepository;

    /**
     * @var SaveCashbackTrackingEntityObserver
     */
    protected $saveCashbackTrackingEntityObserver;

    /**
     * @var Mockery\MockInterface
     */
    protected $eventObserver;

    protected function setUp()
    {
        $this->cashbackTrackingFactory = Mockery::mock(CashbackTrackingFactory::class);
        $this->cashbackTrackingRepository = Mockery::mock(CashbackTrackingRepositoryInterface::class);

        $this->saveCashbackTrackingEntityObserver = new SaveCashbackTrackingEntityObserver(
            $this->cashbackTrackingFactory,
            $this->cashbackTrackingRepository
        );

        $this->eventObserver = Mockery::mock(Observer::class);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testExecuteWithCashbackEntityExist()
    {
        $event = Mockery::mock(Event::class);
        $this->eventObserver
            ->shouldReceive('getEvent')
            ->andReturn($event);

        $order = Mockery::mock(Order::class);

        $event->shouldReceive('getData')
            ->with('order')
            ->andReturn($order);

        $orderPayment = Mockery::mock(\Magento\Sales\Api\Data\OrderPaymentInterface::class);

        $order->shouldReceive('getPayment')
            ->andReturn($orderPayment);


        $additionalInformation = [
            'partner' => 'shopback',
            'partner_parameter' => 'abc123'
        ];

        $orderPayment->shouldReceive('getAdditionalInformation')
            ->andReturn($additionalInformation);

        $cashbackEntity = Mockery::mock(CashbackTracking::class);

        $order->shouldReceive('getEntityId')
            ->andReturn(1);

        $this->cashbackTrackingRepository
            ->shouldReceive('getByOrderId')
            ->with(1)
            ->andReturn($cashbackEntity);

        $cashbackEntity->shouldReceive('setPartner')
            ->with($additionalInformation['partner'])
            ->andReturnSelf();

        $cashbackEntity->shouldReceive('setPartnerParameter')
            ->with($additionalInformation['partner_parameter'])
            ->andReturnSelf();

        $this->cashbackTrackingRepository
            ->shouldReceive('save')
            ->with($cashbackEntity)
            ->andReturnSelf();

        $actualResult = $this->saveCashbackTrackingEntityObserver->execute($this->eventObserver);
        $this->assertSame($this->saveCashbackTrackingEntityObserver, $actualResult);
    }

    public function testExecuteWithCashbackEntityNotExist()
    {
        $event = Mockery::mock(Event::class);
        $this->eventObserver
            ->shouldReceive('getEvent')
            ->andReturn($event);

        $order = Mockery::mock(Order::class);

        $event->shouldReceive('getData')
            ->with('order')
            ->andReturn($order);

        $orderPayment = Mockery::mock(\Magento\Sales\Api\Data\OrderPaymentInterface::class);

        $order->shouldReceive('getPayment')
            ->andReturn($orderPayment);


        $additionalInformation = [
            'partner' => 'shopback',
            'partner_parameter' => 'abc123'
        ];

        $orderPayment->shouldReceive('getAdditionalInformation')
            ->andReturn($additionalInformation);

        $order->shouldReceive('getEntityId')
            ->andReturn(1);

        $this->cashbackTrackingRepository
            ->shouldReceive('getByOrderId')
            ->with(1)
            ->andThrow(new NoSuchEntityException(
                __("The entity_id was requested doesn't exist")
            ));

        $cashbackEntity = Mockery::mock(CashbackTracking::class);
        $this->cashbackTrackingFactory
            ->shouldReceive('create')
            ->andReturn($cashbackEntity);

        $cashbackEntity->shouldReceive('setOrderId')
            ->with(1)
            ->andReturnSelf();

        $cashbackEntity->shouldReceive('setPartner')
            ->with($additionalInformation['partner'])
            ->andReturnSelf();

        $cashbackEntity->shouldReceive('setPartnerParameter')
            ->with($additionalInformation['partner_parameter'])
            ->andReturnSelf();

        $this->cashbackTrackingRepository
            ->shouldReceive('save')
            ->with($cashbackEntity)
            ->andReturnSelf();

        $actualResult = $this->saveCashbackTrackingEntityObserver->execute($this->eventObserver);
        $this->assertSame($this->saveCashbackTrackingEntityObserver, $actualResult);
    }
}

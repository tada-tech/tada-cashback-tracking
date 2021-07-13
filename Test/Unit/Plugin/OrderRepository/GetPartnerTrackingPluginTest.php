<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Test\Unit\Plugin\OrderRepository;

use Magento\Sales\Api\Data\OrderExtension;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use PHPUnit\Framework\TestCase;
use Mockery;
use Tada\CashbackTracking\Model\CashbackTracking;
use Tada\CashbackTracking\Model\Plugin\OrderRepository\GetPartnerTrackingPlugin;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;

class GetPartnerTrackingPluginTest extends TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $cashbackRepository;

    /**
     * @var Mockery\MockInterface
     */
    protected $subject;

    /**
     * @var Mockery\MockInterface
     */
    protected $resultOrder;

    /**
     * @var GetPartnerTrackingPlugin
     */
    protected $getPartnerTrackingPlugin;

    /**
     * @var Mockery\MockInterface
     */
    protected $orderExtensionFactory;


    public function setUp()
    {
        $this->cashbackRepository = Mockery::mock(CashbackTrackingRepositoryInterface::class);
        $this->orderExtensionFactory = Mockery::mock(\Magento\Sales\Api\Data\OrderExtensionFactory::class);

        $this->subject = Mockery::mock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->resultOrder = Mockery::mock(\Magento\Sales\Api\Data\OrderInterface::class);

        $this->getPartnerTrackingPlugin = new GetPartnerTrackingPlugin(
            $this->cashbackRepository,
            $this->orderExtensionFactory
        );
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testAfterGetWithExtensionAttributeExist()
    {
        $orderEntityId = 2;

        $this->resultOrder->shouldReceive('getEntityId')
            ->andReturn($orderEntityId);

        /** @var CashbackTracking $cashbackModel */
        $cashbackModel = Mockery::mock(CashbackTracking::class);
        $this->cashbackRepository
            ->shouldReceive('getByOrderId')
            ->with($orderEntityId)
            ->andReturn($cashbackModel);

        $extensionAttributes = Mockery::mock(\Magento\Sales\Api\Data\OrderExtensionInterface::class);
        $this->resultOrder->shouldReceive('getExtensionAttributes')
            ->andReturn($extensionAttributes);

        $extensionAttributes->shouldReceive('setPartnerTracking')
            ->with($cashbackModel)
            ->andReturnSelf();

        $this->resultOrder->shouldReceive('setExtensionAttributes')
            ->with($extensionAttributes)
            ->andReturnSelf();

        $actualResult = $this->getPartnerTrackingPlugin->afterGet($this->subject, $this->resultOrder);

        $this->assertEquals($this->resultOrder, $actualResult);
    }

    public function testAfterGetWithExtensionAttributeNotExist()
    {
        $orderEntityId = 2;

        $this->resultOrder->shouldReceive('getEntityId')
            ->andReturn($orderEntityId);

        /** @var CashbackTracking $cashbackModel */
        $cashbackModel = Mockery::mock(CashbackTracking::class);
        $this->cashbackRepository
            ->shouldReceive('getByOrderId')
            ->with($orderEntityId)
            ->andReturn($cashbackModel);

        $this->resultOrder->shouldReceive('getExtensionAttributes')
            ->andReturnNull();


        $extensionAttributes = Mockery::mock(OrderExtension::class);

        $this->orderExtensionFactory->shouldReceive('create')
            ->andReturn($extensionAttributes);


        $extensionAttributes->shouldReceive('setPartnerTracking')
            ->with($cashbackModel)
            ->andReturnSelf();

        $this->resultOrder->shouldReceive('setExtensionAttributes')
            ->with($extensionAttributes)
            ->andReturnSelf();

        $actualResult = $this->getPartnerTrackingPlugin->afterGet($this->subject, $this->resultOrder);

        $this->assertEquals($this->resultOrder, $actualResult);
    }
}

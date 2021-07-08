<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Test\Integration;

use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Tada\CashbackTracking\Model\CashbackTracking;
use Tada\CashbackTracking\Model\CashbackTrackingFactory;

class CashbackTrackingRepositoryTest extends TestCase
{
    /**
     * @var CashbackTrackingRepositoryInterface
     */
    protected $CashbackTrackingRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CashbackTrackingFactory
     */
    protected $CashbackTrackingFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->CashbackTrackingRepository = Bootstrap::getObjectManager()->get(CashbackTrackingRepositoryInterface::class);
        $this->searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);
        $this->CashbackTrackingFactory = Bootstrap::getObjectManager()->get(CashbackTrackingFactory::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDataFixture Tada_CashbackTracking::Test/_files/template_entities.php
     */
    public function testGetList()
    {
        $data = [
            'attribute_one' => "Attribute Number One",
            'attribute_two' => 10,
            'attribute_three' => 24
        ];
        /** @var SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(CashbackTrackingInterface::ATTRIBUTE_ONE, $data['attribute_one'])
            ->create();

        $results = $this->CashbackTrackingRepository->getList($searchCriteria)->getItems();
        $firstItem = current($results);
        unset($firstItem['entity_id']);

        $this->assertEquals($data, $firstItem->getData());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDataFixture Tada_CashbackTracking::Test/_files/template_entities.php
     */
    public function testDelete()
    {
        /** @var SearchCriteriaInterface $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(CashbackTrackingInterface::ATTRIBUTE_ONE, "Attribute Number One")
            ->create();

        $results = $this->CashbackTrackingRepository->getList($searchCriteria)->getItems();
        $firstItem = current($results);
        $entityId = (int) $firstItem->getEntityId();

        $this->CashbackTrackingRepository->delete($firstItem);

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('No such entity with entityId = '. $entityId);
        $this->CashbackTrackingRepository->get($entityId);
    }

    public function testSaveWithCreate()
    {
        $data = [
            'attribute_one' => 'MAFL#R!FFAFa',
            'attribute_two' => 53,
            'attribute_three' => 443
        ];

        /** @var CashbackTracking $model */
        $model =  $this->CashbackTrackingFactory->create();
        $model->setData($data);
        $newModel = $this->CashbackTrackingRepository->save($model);

        $entityId = $newModel->getEntityId();
        $this->assertNotNull($entityId);
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testSaveWithUpdate()
    {
        $data = [
            'attribute_one' => 'MAFL#R!FFAFa',
            'attribute_two' => 53,
            'attribute_three' => 443
        ];

        /** @var CashbackTracking $model */
        $model =  $this->CashbackTrackingFactory->create();
        $model->setData($data);
        $newModel = $this->CashbackTrackingRepository->save($model);
        $entityId = (int) $newModel->getEntityId();

        $attributeOneNeedUpdate = ")%!*%!(f1f1";
        /** @var CashbackTracking $model */
        $model = $this->CashbackTrackingRepository->get($entityId);
        $model->setAttributeOne($attributeOneNeedUpdate);
        $updatedModel = $this->CashbackTrackingRepository->save($model);

        $this->assertEquals($entityId, $updatedModel->getEntityId());
        $this->assertEquals($attributeOneNeedUpdate, $updatedModel->getAttributeOne());
    }
}

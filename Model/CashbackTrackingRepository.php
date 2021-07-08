<?php
declare(strict_types=1);

namespace Tada\CashbackTracking\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tada\CashbackTracking\Api\Data\CashbackTrackingInterface;
use Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface;
use Tada\CashbackTracking\Api\Data\CashbackTrackingSearchResultInterface;
use Tada\CashbackTracking\Model\ResourceModel\CashbackTracking\Collection;

class CashbackTrackingRepository implements CashbackTrackingRepositoryInterface
{
    /**
     * @var \Tada\CashbackTracking\Model\CashbackTracking[]
     */
    private $registry = [];

    /**
     * @var \Tada\CashbackTracking\Model\CashbackTracking[]
     */
    private $orderIdRegistry = [];

    /**
     * @var ResourceModel\CashbackTracking
     */
    private $resourceModel;

    /**
     * @var CashbackTrackingFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel\CashbackTracking\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var \Tada\CashbackTracking\Api\Data\CashbackTrackingSearchResultInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * CashbackTrackingRepository constructor.
     * @param ResourceModel\CashbackTracking $resourceModel
     * @param CashbackTrackingFactory $modelFactory
     * @param ResourceModel\CashbackTracking\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Tada\CashbackTracking\Api\Data\CashbackTrackingSearchResultInterfaceFactory $searchResultInterfaceFactory
     */
    public function __construct(
        \Tada\CashbackTracking\Model\ResourceModel\CashbackTracking $resourceModel,
        \Tada\CashbackTracking\Model\CashbackTrackingFactory $modelFactory,
        \Tada\CashbackTracking\Model\ResourceModel\CashbackTracking\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \Tada\CashbackTracking\Api\Data\CashbackTrackingSearchResultInterfaceFactory $searchResultInterfaceFactory
    ) {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultInterfaceFactory;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return CashbackTrackingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process(
            $collection
        );
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CashbackTrackingSearchResultInterface $searchResults */
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param CashbackTrackingInterface $object
     * @return CashbackTrackingInterface
     * @throws CouldNotSaveException
     */
    public function save(CashbackTrackingInterface $object)
    {
        try {
            $this->resourceModel->save($object);
        } catch (\Exception $e) {
            if ($object->getEntityId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save $object with ID %1. Error: %2',
                        [$object->getEntityId(), $e->getMessage()]
                    )
                );
            }

            throw new CouldNotSaveException(
                __('Unable to save new $object. Error: %1', $e->getMessage())
            );
        }
        return $object;
    }

    /**
     * @param int $entityId
     * @param bool $forceReload
     * @return CashbackTrackingInterface|CashbackTracking
     * @throws NoSuchEntityException
     */
    public function get(int $entityId, bool $forceReload = false)
    {
        if (isset($this->registry[$entityId]) && !$forceReload) {
            return $this->registry[$entityId];
        }

        $model = $this->modelFactory->create();
        $this->resourceModel->load($model, $entityId);

        if (!$model->getEntityId()) {
            throw NoSuchEntityException::singleField('entityId', $entityId);
        }

        $this->registry[$entityId] = $model;

        return $model;
    }

    /**
     * @param int $orderId
     * @param bool $forceReload
     * @return CashbackTrackingInterface|CashbackTracking
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByOrderId(int $orderId, bool $forceReload = false)
    {
        if (isset($this->orderIdRegistry[$orderId]) && !$forceReload) {
            return $this->orderIdRegistry[$orderId];
        }

        $model = $this->modelFactory->create();

        $entityId = $this->resourceModel->getIdByOrderId($orderId);

        if (!$entityId) {
            throw new NoSuchEntityException(
                __("The entity_id was requested doesn't exist")
            );
        }

        $this->resourceModel->load($model, $entityId);
        $this->orderIdRegistry[$orderId] = $model;

        return $model;
    }

    /**
     * @param CashbackTrackingInterface $object
     * @return CashbackTrackingInterface
     * @throws \Exception
     */
    public function delete(CashbackTrackingInterface $object): CashbackTrackingInterface
    {
        $entityId = $object->getEntityId();
        unset($this->registry[$entityId]);
        unset($this->orderIdRegistry[$entityId]);
        $this->resourceModel->delete($object);
        return $object;
    }
}

<?php
declare(strict_types=1);

use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/**
 * @var \Tada\CashbackTracking\Api\Data\CashbackTrackingInterfaceFactory $CashbackTrackingFactory
 */
$CashbackTrackingFactory = $objectManager->get(\Tada\CashbackTracking\Api\Data\CashbackTrackingInterfaceFactory::class);

/** @var \Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface $CashbackTrackingRepository */
$CashbackTrackingRepository = $objectManager->get(\Tada\CashbackTracking\Api\CashbackTrackingRepositoryInterface::class);

$data = [
    'attribute_one' => "Attribute Number One",
    'attribute_two' => 10,
    'attribute_three' => 23.53
];

/** @var \Tada\CashbackTracking\Api\Data\CashbackTrackingInterface $entity */
$entity = $CashbackTrackingFactory->create();
$entity->setData($data);
$newEntity = $CashbackTrackingRepository->save($entity);

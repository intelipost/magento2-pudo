<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model;

use Magento\Framework\Model\AbstractModel;

class Items extends AbstractModel
{

protected $itemsFactory;
protected $itemsInterfaceFactory;
protected $itemsResultInterfaceFactory;

protected $storesFactory;

public function __construct(
    \Magento\Framework\Model\Context $context,
    \Magento\Framework\Registry $registry,
    \Intelipost\Pickup\Model\ItemsFactory $itemsFactory,
    \Intelipost\Pickup\Api\Data\ItemsInterfaceFactory $itemsInterfaceFactory,
    \Intelipost\Pickup\Api\Data\ItemsResultInterfaceFactory $itemsResultInterfaceFactory,
    \Intelipost\Pickup\Model\StoresFactory $storesFactory
)
{
    $this->itemsFactory = $itemsFactory;
    $this->itemsInterfaceFactory = $itemsInterfaceFactory;
    $this->itemsResultInterfaceFactory = $itemsResultInterfaceFactory;

    $this->storesFactory = $storesFactory;

    parent::__construct($context, $registry);
}

protected function _construct()
{
    $this->_init('Intelipost\Pickup\Model\Resource\Items');
}

/*
 * {@inheritdoc}
 */
public function getList()
{
    $collection = $this->getCollection();
    $data = null;

    foreach ($collection as $child)
    {
        $data [] = $child->getData();
    }

    $result = $this->itemsResultInterfaceFactory->create();
    $result->setItems($data);

    return $result;
}

/*
 * {@inheritdoc}
 */
public function getInfo($id)
{
    $object = $this->itemsFactory->create()->load($id);

    $result = $this->itemsInterfaceFactory->create();
    $result->setId($object->getId());
    $result->setStoreId($object->getStoreId());
    $result->setIdLoja($object->getIdLoja());
    $result->setDepartureDate($object->getDepartureDate());
    $result->setArrivalDate($object->getArrivalDate());
    $result->setOperationTime($object->getOperationTime());

    return $result;
}

/*
 * {@inheritdoc}
 */
public function saveItem($items)
{
    foreach ($items as $item)
    {
        $object = $this->itemsFactory->create();

        $object->setId ($item->getId());
        // $object->setStoreId ($item->getStoreId());
        $object->setIdLoja ($item->getIdLoja());
        $object->setArrivalDate ($item->getArrivalDate());
        $object->setDepartureDate ($item->getDepartureDate());
        $object->setOperationTime ($item->getOperationTime());

        $store  = $this->storesFactory->create()->load($item->getIdLoja(), 'id_loja');
        $object->setStoreId ($store->getId());

        $object->save();
    }

    return true;
}

/*
 * {@inheritdoc}
 */
public function deleteItem($id)
{
    $collection = $this->itemsFactory->create()->getCollection();
    $collection->getSelect()->where("id_loja = '{$id}'");

    foreach ($collection as $item)
    {
        $object = $this->itemsFactory->create()->load($item->getId());
	$object->delete();
    }

    return true;
}

}


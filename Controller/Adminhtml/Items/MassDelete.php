<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Items;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Intelipost\Pickup\Controller\Adminhtml\Items
{

protected $redirectUrl = 'intelipost_pickup/items/index';

public function execute()
{
    try
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        return $this->massAction($collection);
    }
    catch (\Exception $e)
    {
        $this->messageManager->addError($e->getMessage());

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath($this->redirectUrl);
    }
}

protected function massAction(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection)
{
    $itemsDeleted = 0;
    foreach ($collection->getAllIds() as $itemId)
    {
        $item = $this->itemsFactory->create()->load($itemId);
        $item->delete();
        $itemsDeleted++;
    }

    if ($itemsDeleted)
    {
        $this->messageManager->addSuccess(__('A total of %1 record(s) were deleted.', $itemsDeleted));
    }

    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    $resultRedirect->setPath($this->getComponentRefererUrl());

    return $resultRedirect;
}

protected function getComponentRefererUrl()
{
    return $this->filter->getComponentRefererUrl()?: 'intelipost_pickup/items/index';
}

}


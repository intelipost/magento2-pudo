<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Items;

class Edit extends \Intelipost\Pickup\Controller\Adminhtml\Items
{

public function execute()
{
    $itemId = $this->_initCurrentItem();
    $itemData = null;
    $item = null;

    $isExistingItem = (bool) $itemId;
    if ($isExistingItem)
    {
        try
        {
            $item = $this->itemsFactory->create()->load($itemId);
            $itemData = $item->getData();
            $itemData['entity_id'] = $itemId;
        }
        catch (NoSuchEntityException $e)
        {
            $this->messageManager->addException($e, __('Something went wrong while editing the item.'));

            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('intelipost_pickup/items/index');

            return $resultRedirect;
        }
    }

    $this->_getSession()->setIntelipostPickupItemData($itemData);

    $resultPage = $this->resultPageFactory->create();
    $resultPage->setActiveMenu('Intelipost_Pickup::items');

    if ($isExistingItem)
    {
        $resultPage->getConfig()->getTitle()->prepend($item->getStoreName());
    }
    else
    {
        $resultPage->getConfig()->getTitle()->prepend(__('New Item'));
    }

    return $resultPage;
}

}


<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Stores;

class Edit extends \Intelipost\Pickup\Controller\Adminhtml\Stores
{

public function execute()
{
    $storeId = $this->_initCurrentItem();
    $storeData = null;
    $store = null;

    $isExistingStore = (bool) $storeId;
    if ($isExistingStore)
    {
        try
        {
            $store = $this->storesFactory->create()->load($storeId);
            $storeData = $store->getData();
            $storeData['entity_id'] = $storeId;
        }
        catch (NoSuchEntityException $e)
        {
            $this->messageManager->addException($e, __('Something went wrong while editing the store.'));

            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('intelipost_pickup/stores/index');

            return $resultRedirect;
        }
    }

    $this->_getSession()->setIntelipostPickupStoreData($storeData);

    $resultPage = $this->resultPageFactory->create();
    $resultPage->setActiveMenu('Intelipost_Pickup::istores');

    if ($isExistingStore)
    {
        $resultPage->getConfig()->getTitle()->prepend($store->getName());
    }
    else
    {
        $resultPage->getConfig()->getTitle()->prepend(__('New Store'));
    }

    return $resultPage;
}

}


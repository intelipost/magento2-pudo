<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Stores;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Intelipost\Pickup\Controller\Adminhtml\Stores
{

public function execute()
{
    $resultRedirect = $this->resultRedirectFactory->create();
    $formKeyIsValid = $this->_formKeyValidator->validate($this->getRequest());
    $isPost = $this->getRequest()->isPost();
    if (!$formKeyIsValid || !$isPost)
    {
        $this->messageManager->addError(__('Store could not be deleted.'));

        return $resultRedirect->setPath('intelipost_pickup/stores/index');
    }

    $storeId = $this->_initCurrentItem();
    if (!empty($storeId))
    {
        try
        {
            $store = $this->storesFactory->create()->load($storeId);
            $store->delete();
            $this->messageManager->addSuccess(__('You deleted the store.'));
        }
        catch (\Exception $exception)
        {
            $this->messageManager->addError($exception->getMessage());
        }
    }

    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

    return $resultRedirect->setPath('intelipost_pickup/stores/index');
}

}


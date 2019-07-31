<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Items;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Intelipost\Pickup\Controller\Adminhtml\Items
{

public function execute()
{
    $resultRedirect = $this->resultRedirectFactory->create();
    $formKeyIsValid = $this->_formKeyValidator->validate($this->getRequest());
    $isPost = $this->getRequest()->isPost();
    if (!$formKeyIsValid || !$isPost)
    {
        $this->messageManager->addError(__('Item could not be deleted.'));

        return $resultRedirect->setPath('intelipost_pickup/items/index');
    }

    $itemId = $this->_initCurrentItem();
    if (!empty($itemId))
    {
        try
        {
            $item = $this->itemsFactory->create()->load($itemId);
            $item->delete();
            $this->messageManager->addSuccess(__('You deleted the item.'));
        }
        catch (\Exception $exception)
        {
            $this->messageManager->addError($exception->getMessage());
        }
    }

    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

    return $resultRedirect->setPath('intelipost_pickup/items/index');
}

}


<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Items;

use Intelipost\Pickup\Controller\Adminhtml\RegistryConstants;

class Save extends \Intelipost\Pickup\Controller\Adminhtml\Items
{

public function execute()
{
    $returnToEdit = false;
    $originalRequestData = $this->getRequest()->getPostValue();
    $itemId = isset($originalRequestData['item']['id'])
        ? $originalRequestData['item']['id']
        : null;
    if ($originalRequestData)
    {
        try
        {
            if ($itemId)
            {
                $item = $this->itemsFactory->create()->load($itemId);
            }
            else
            {
                $item = $this->itemsFactory->create();
            }

            $item->addData($originalRequestData['item']);
            $item->save();

            $this->_getSession()->unsIntelipostPickupItemsData();

            $itemId = $item->getId();
            $this->coreRegistry->register(RegistryConstants::CURRENT_INTELIPOST_PICKUP_ITEM_ID, $itemId);

            $this->messageManager->addSuccess(__('You saved the item.'));

            $returnToEdit = (bool) $this->getRequest()->getParam('back', false);
        }
        catch (\Magento\Framework\Validator\Exception $exception)
        {
            $messages = $exception->getMessages();
            if (empty($messages))
            {
                $messages = $exception->getMessage();
            }

            $this->_addSessionErrorMessages($messages);
            $this->_getSession()->setCustomerData($originalRequestData);

            $returnToEdit = true;
        }
        catch (LocalizedException $exception)
        {
            $this->_addSessionErrorMessages($exception->getMessage());

            $this->_getSession()->setIntelipostPickupData($originalRequestData);

            $returnToEdit = true;
        }
        catch (\Exception $exception)
        {
            $this->messageManager->addException($exception, __('Something went wrong while saving the item.'));

            $this->_getSession()->setIntelipostPickupItemData($originalRequestData);

            $returnToEdit = true;
        }
    }

    $resultRedirect = $this->resultRedirectFactory->create();

    if ($returnToEdit)
    {
        if ($itemId) {
            $resultRedirect->setPath(
                'intelipost_pickup/items/edit',
                ['id' => $itemId, '_current' => true]
            );
        }
        else
        {
            $resultRedirect->setPath(
                'intelipost_pickup/items/new',
                ['_current' => true]
            );
        }
    }
    else
    {
        $resultRedirect->setPath('intelipost_pickup/items/index');
    }

    return $resultRedirect;
}

}


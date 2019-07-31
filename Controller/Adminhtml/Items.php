<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml;

abstract class Items extends \Magento\Backend\App\Action
{

protected $coreRegistry;

protected $filter;

protected $resultForwardFactory;

protected $resultPageFactory;

protected $itemsFactory;

public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    \Magento\Framework\Registry $coreRegistry,
    \Magento\Ui\Component\MassAction\Filter $filter,
    \Intelipost\Pickup\Model\ItemsFactory $itemsFactory,
    \Intelipost\Pickup\Model\Resource\Items\CollectionFactory $collectionFactory
)
{
    $this->coreRegistry = $coreRegistry;
    $this->filter = $filter;
    $this->resultForwardFactory = $resultForwardFactory;
    $this->resultPageFactory = $resultPageFactory;

    $this->itemsFactory = $itemsFactory;
    $this->collectionFactory = $collectionFactory;

    parent::__construct($context);
}

protected function _initCurrentItem()
{
    $itemId = $this->getRequest()->getParam('id');

    if ($itemId)
    {
        $this->coreRegistry->register(RegistryConstants::CURRENT_INTELIPOST_PICKUP_ITEM_ID, $itemId);
    }

    return $itemId;
}

protected function _isAllowed()
{
    return $this->_authorization->isAllowed('Intelipost_Pickup::items');
}

}


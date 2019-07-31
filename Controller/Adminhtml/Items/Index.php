<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Items;

class Index extends \Intelipost\Pickup\Controller\Adminhtml\Items
{

public function execute()
{
	$resultPage = $this->resultPageFactory->create();

	$resultPage->setActiveMenu('Intelipost_Pickup::items');
	
	$resultPage->getConfig()->getTitle()->prepend(__('Manage Items'));

	$resultPage->addBreadcrumb(__('Manage Items'), __('Manage Pickup Items'));

	return $resultPage;
}

protected function _isAllowed()
{
	return $this->_authorization->isAllowed('Intelipost_Pickup::items');
}

}


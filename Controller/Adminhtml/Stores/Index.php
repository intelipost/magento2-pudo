<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Stores;

class Index extends \Intelipost\Pickup\Controller\Adminhtml\Stores
{

public function execute()
{
	$resultPage = $this->resultPageFactory->create();

	$resultPage->setActiveMenu('Intelipost_Pickup::istores');
	
	$resultPage->getConfig()->getTitle()->prepend(__('Manage Stores'));

	$resultPage->addBreadcrumb(__('Manage Stores'), __('Manage Pickup Stores'));

	return $resultPage;
}

protected function _isAllowed()
{
	return $this->_authorization->isAllowed('Intelipost_Pickup::istores');
}

}


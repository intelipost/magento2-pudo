<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Store;

class Index extends \Magento\Framework\App\Action\Action
{

protected $_helper;
protected $_storesFactory;
protected $_resultPageFactory;

public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    \Intelipost\Pickup\Helper\Data $helper,
    \Intelipost\Pickup\Model\StoresFactory $storesFactory
)
{
    $this->_helper = $helper;
    $this->_storesFactory = $storesFactory;
    $this->_resultPageFactory = $resultPageFactory;

    parent::__construct ($context);
}

public function execute()
{
    $cep = $this->getRequest()->getParam('cep');
    $html = $this->_helper->getFormPudo ($cep);

    $resultPage = $this->_resultPageFactory->create();
    $this->getResponse()->setBody(
        $html
    );
}

}


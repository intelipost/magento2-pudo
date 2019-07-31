<?php

namespace Intelipost\Pickup\Controller\Pickup;

use Magento\Framework\App\ResponseInterface;


class Cities extends \Magento\Framework\App\Action\Action
{
    protected $_helper;
    protected $_resultPageFactory;

    const API_METHOD = 'pudos/states/cities';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Intelipost\Basic\Helper\Api $helper
    )
    {
        $this->_helper = $helper;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $html = null;
        $data = array();

        $state = $this->getRequest()->getParam('state');

        //fazer a requisicao via curl
        $_response = json_decode($this->_helper->apiRequest('GET', self::API_METHOD."?state=".$state));

        foreach($_response->content as $item){

           $html .= "<option value='".$item."'>".$item."</option>";
        }

        $this->_resultPageFactory->create();
        $this->getResponse()->setBody(
            $html
        );
    }
}
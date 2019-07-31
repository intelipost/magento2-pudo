<?php

namespace Intelipost\Pickup\Controller\Pickup;

use Magento\Framework\App\ResponseInterface;


class Pudo extends \Magento\Framework\App\Action\Action
{
    protected $_helper;
    protected $_resultPageFactory;

    const API_METHOD = 'pudos';

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

        if($this->getRequest()->getParam('zipcode')){
            $data['zipcode']    = $this->getRequest()->getParam('zipcode');
            $data['type']       = $this->getRequest()->getParam('type');
            $data['nearest']    = $this->getRequest()->getParam('nearest');
        } else {
            $data['type']       = $this->getRequest()->getParam('type');
            $data['state']    = $this->getRequest()->getParam('state');
            $data['city']    = $this->getRequest()->getParam('city');
        }

        //fazer a requisicao via curl
        $_response = json_decode($this->_helper->apiRequest('GET', self::API_METHOD, $data));

        foreach($_response->content->items as $item){

            $html .= "<p><input name='intelipost_pudo[]' class='pudo_id' type='radio' value='".$item->id."'>".$item->name." </p>";
        }

        $this->_resultPageFactory->create();
        $this->getResponse()->setBody(
            $html
        );
    }
}
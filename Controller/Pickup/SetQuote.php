<?php

namespace Intelipost\Pickup\Controller\Pickup;

use Magento\Framework\App\ResponseInterface;


class SetQuote extends \Magento\Framework\App\Action\Action
{
    protected $_helper;
    protected $_resultPageFactory;
    protected $_checkoutSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Intelipost\Basic\Helper\Api $helper
    )
    {
        $this->_helper = $helper;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {

        $pudoId = $this->getRequest()->getParam('pudo_id');
        $response = $this->_helper->apiRequest($this->_helper::GET, 'pudos/'. $pudoId);
        $this->_checkoutSession->setPickupInfo($response);

    }
}
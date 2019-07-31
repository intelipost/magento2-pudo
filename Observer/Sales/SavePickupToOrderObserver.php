<?php


namespace Intelipost\Pickup\Observer\Sales;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;



class SavePickupToOrderObserver implements ObserverInterface
{
    protected $_objectManager;
    protected $_checkoutSession;
    protected $orderFactory;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory
    )
    {
        $this->_objectManager = $objectmanager;
        $this->_checkoutSession = $checkoutSession;
        $this->orderFactory = $orderFactory;
    }


    public function execute(EventObserver $observer)
    {
        $order = $observer->getOrder();
        $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $quoteRepository->get($order->getQuoteId());
        $order->setIntelipostQuote( $this->_checkoutSession->getPickupInfo() );

        return $this;

    }
}
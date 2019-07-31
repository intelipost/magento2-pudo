<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Observer\Sales;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManager;
use Intelipost\Pickup\Model\Stores;
use Intelipost\Pickup\Model\Items;
use Intelipost\Quote\Helper\Data;
use Intelipost\Quote\Model\Quote;
use Magento\Sales\Model\Order;
//use Webjump\IntelipostBasicExtended\Helper\Config;
use Corra\Pickupstore\Model\Pickupstores;

class OrderSaveBefore implements ObserverInterface
{
    protected $_cart;
    /**
     * @var Pickupstores
     */
    protected  $_pickupStoreManager;

    /**
     * @var \Intelipost\Pickup\Model\Stores
     */
    protected $_pickupStores;

    /**
     * @var \Intelipost\Pickup\Model\Items
     */
    protected $_pickupItems;

    /**
     * @var \Intelipost\Quote\Helper\Data
     */
    protected $_intelipostHelper;

    /**
     * @var \Intelipost\Quote\Model\Quote
     */
    protected $_intelipostQuote;

    /**
     * @var \Magento\Framework\Session\SessionManager
     */
    protected $_sessionManager;

    /**
     * @var
     */
    protected $_quoteRepository;
    
    /**
     * @var \Webjump\IntelipostBasicExtended\Helper\Config
     */
    //protected $_incrementShippingDateHelper;
    
    public function __construct(
        Stores $pickupStores,
        Items $pickupItems,
        Data $intelipostHelper,
        Quote $intelipostQuote,
        SessionManager $sessionManager
        //Config $incrementShippingDateHelper
    )
    {
        $this->_pickupStores = $pickupStores;
        $this->_pickupItems = $pickupItems;
        $this->_intelipostHelper = $intelipostHelper;
        $this->_intelipostQuote = $intelipostQuote;
        $this->_sessionManager = $sessionManager;
        //$this->_incrementShippingDateHelper = $incrementShippingDateHelper;
    }

    public function execute(Observer $observer)
    {
        /**
         * @var Order $orderInstance
         */
        $orderInstance = $observer->getOrder();
        $result = null;

        $shippingMethod = explode('_', $orderInstance->getShippingMethod());
        if(!empty($shippingMethod[0]) && !strcmp($shippingMethod[0], 'pickup') /* carrierName */
            && !empty($shippingMethod[1]) && !strcmp($shippingMethod[1], 'pickup') /* methodName */
            && !empty($shippingMethod[2]) /* pickupStoreId */
        )
        {
            $pickupStoreId = $shippingMethod[2];

            $intelipostQuoteCollection = $this->_intelipostHelper->getResultQuotes(\Intelipost\Quote\Helper\Data::RESULT_PICKUP);

            if (empty($intelipostQuoteCollection) && !count($intelipostQuoteCollection) /* !$intelipostQuoteCollection->count() */) return;

            $pickupStore = $this->_pickupStores->load($pickupStoreId);

            $result ['store'] = $pickupStore->getData();

            $orderInstance->getShippingAddress()
                ->setStreet(array(
                    $pickupStore->getAddress(),
                    $pickupStore->getNumber(),
                    $pickupStore->getComplement(),
                    $pickupStore->getStoreNeighborhood() // $pickupStore->getDistrict()
                ))
                ->setPostcode($pickupStore->getZipcode())
                ->setCity($pickupStore->getCity())
                ->setRegion($pickupStore->getState());
            $pickUpStoreItem = $this->getSelectedStore($pickupStoreId, $this->getCart($orderInstance->getQuoteId()));

            $result['item'] = $pickUpStoreItem->getData();
        }

        $orderInstance->setIntelipostPickup(
            json_encode($result)
        );
    }

    /**
     * @param $id
     * @param $cart
     * @return DataObject
     */
    public function getSelectedStore($id, $cart) {
        $minDate = $this->getPickStoreManager()->getMinDate($cart);
        $collection = $this->_pickupItems->getCollection();
        $collection->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['*', 'STR_TO_DATE(main_table.departure_date, "%d/%m/%Y") as departure']);
        $collection->getSelect()->where(
                "store_id = {$id} and STR_TO_DATE(main_table.departure_date, '%d/%m/%Y') > STR_TO_DATE('{$minDate}', '%d/%m/%Y')"
        );
        $collection->getSelect()->order('departure asc')->limit(1);
        return $collection->getFirstItem();
    }

    /**
     * @return Items
     */
    public function getPickupStoreItem()
    {
        return $this->_pickupItems;
    }

    public function getPickStoreManager()
    {
        if(!$this->_pickupStoreManager){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->_pickupStoreManager = $objectManager->create(Pickupstores::class);
        }
        return $this->_pickupStoreManager;
    }

    public function getCart($quoteId)
    {
        $cart = new DataObject();
        try{
            $cart->addData(array('quote' => $this->getQuoteRepository()->get($quoteId)));
        }catch (\Exception $e){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            return $objectManager->create(\Magento\Checkout\Model\Cart::class);
        }
        return $cart;
    }

    public function getQuoteRepository()
    {
        if(!$this->_quoteRepository){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->_quoteRepository = $objectManager->create(\Magento\Quote\Api\CartRepositoryInterface::class);
        }
        return $this->_quoteRepository;
    }
}


<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;


class Pickup
    extends \Magento\Shipping\Model\Carrier\AbstractCarrier
    implements \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'pickup';
    protected $_rateResultFactory;
    protected $_rateMethodFactory;
    protected $_rateErrorFactory;
    protected $_scopeConfig;
    protected $_itemsFactory;
    protected $_checkoutSession;
    protected $_helper;
    protected $_requestInterface;

    const API_METHOD = 'pudos?';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Intelipost\Pickup\Model\ItemsFactory $itemsFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Intelipost\Basic\Helper\Api $helper,
        array $data = []
    )
    {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_rateErrorFactory = $rateErrorFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_itemsFactory = $itemsFactory;
        $this->_helper = $helper;

        parent::__construct(
            $scopeConfig, $rateErrorFactory, $logger, $data
        );
    }

    public function getAllowedMethods()
    {
        return ['pickup' => $this->getConfigData('name')];
    }

    public function collectRates(RateRequest $request)
    {

        if (!$this->getConfigFlag('active')) {
            return false;
        } else if (!$request->getDestPostcode()) {
            return false;
        }

        $carrierTitle = $this->_scopeConfig->getValue('carriers/pickup/title');

        $reqPickup = $this->_checkoutSession->getPickupData();

        //get neart
        $data = array();
        $data['zipcode'] = $request->getDestPostcode();
        $data['type'] = "store";
        $data['nearest'] = "true";

        $query = http_build_query($data);

        $nearst = json_decode($this->_helper->apiRequest('GET', self::API_METHOD.$query));

        if(!empty($nearst->content->items[0])) {

            $namePickup = $nearst->content->items[0]->name;

            $result = $this->_rateResultFactory->create();

            if ($reqPickup['delivery_method_type'] == 'PICKUP') {

                if(strpos($_SERVER['HTTP_REFERER'],'checkout')){

                    $methodTitle = $carrierTitle . " - " . __('days to deliver') . " : " . $reqPickup['delivery_estimate_business_days'];

                } else {

                    $methodTitle = $namePickup . " - " . __('days to deliver') . " : " . $reqPickup['delivery_estimate_business_days'];

                }

                $method = $this->_rateMethodFactory->create();
                $method->setCarrier($this->_code);
                $method->setCarrierTitle($carrierTitle);

                $method->setMethod($this->_code);
                $method->setMethodTitle($methodTitle);
                $method->setMethodDescription($reqPickup['description']);

                $method->setPrice($reqPickup['final_shipping_cost']);
                $method->setCost($reqPickup['final_shipping_cost']);

                $result->append($method);
            }

            return $result;
        }
    }
}


<?php


namespace Intelipost\Pickup\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Intelipost\Quote\Model\Carrier\Intelipost;

class RewriteIntelipost extends Intelipost
{
    protected $_deliveryMethodType = array('PICKUP','LOCKER');

    public function collectRates(RateRequest $request, $pickup = false)
    {
        $this->logIntelipost('intelipost collectrates');
        if (!$this->getConfigFlag ('active'))
        {
            return false;
        }
        else if (!$request->getDestPostcode ())
        {
            return false;
        }
        // if ($this->_removeQuotes) $this->_helper->removeQuotes($this->_code);

        if ($this->getConfigFlag ('marketplace_enable'))
        {
            if ($this->_helper->isModuleEnabled($this->getConfigData ('marketplace_module_name')) && !strcmp ($this->_code, 'intelipost'))
            {
                return false;
            }
        }

        if ($this->_helper->isModuleEnabled ('Intelipost_PreSales')
            && !strcmp ($this->_code, 'intelipost'))
        {
            return false; // Skip when Intelipost_PreSales module is installed
        }

        $api_key = $request->getIpKey() ? $request->getIpKey() : false;

        // Data
        $originZipcode = $this->_scopeConfig->getValue('general/store_information/postcode');
        $destPostcode = $pickup ? $request->getPickupDestPostcode() : $request->getDestPostcode();

        // Factory
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectProduct = $objectManager->get('Magento\Catalog\Model\ProductFactory');
        $eventManager = $objectManager->create('\Magento\Framework\Event\Manager');
        $eventManager->dispatch(self::EVENT, array(
            'request' => $request,
            'intelipost'  => $this,
        ));

        $originZipcode = $this->_origin_zipcode ? $this->_origin_zipcode : $originZipcode;

        $postData = array(
            'carrier' => $this->_code,
            'origin_zip_code' => preg_replace ('#[^0-9]#', "", $originZipcode),
            'destination_zip_code' => preg_replace ('#[^0-9]#', "", $destPostcode),
        );

        $this->logIntelipost($postData);
        if (strlen ($postData ['destination_zip_code']) != 8) return false;

        // Default Config
        $heightAttribute = $this->_scopeConfig->getValue ('carriers/intelipost/height_attribute');
        $widthAttribute = $this->_scopeConfig->getValue ('carriers/intelipost/width_attribute');
        $lengthAttribute = $this->_scopeConfig->getValue ('carriers/intelipost/length_attribute');

        $useCategoryAttribute = $this->_scopeConfig->getValue ('carriers/intelipost/use_category_attribute');

        $weightUnit = $this->_scopeConfig->getValue ('carriers/intelipost/weight_unit') == 'gr' ? 1000 : 1;
        $weightContingency = intval ($this->_scopeConfig->getValue ('carriers/intelipost/weight_contingency')) / $weightUnit;

        $heightContingency = $this->_scopeConfig->getValue ('carriers/intelipost/height_contingency');
        $widthContingency = $this->_scopeConfig->getValue ('carriers/intelipost/width_contingency');
        $lengthContingency = $this->_scopeConfig->getValue ('carriers/intelipost/length_contingency');

        $estimateDeliveryDate = $this->_scopeConfig->getValue ('carriers/intelipost/estimate_delivery_date');

        $calendarOnlyCheckout = $this->_scopeConfig->getValue ('carriers/intelipost/calendar_only_checkout');
        $pageName = $this->_helper->getPageName();

        $breakOnError = $this->_scopeConfig->getValue ('carriers/intelipost/break_on_error');
        $valueOnZero = $this->_scopeConfig->getValue ('carriers/intelipost/value_on_zero');

        $cartWeight = 0;
        $cartAmount = 0;
        $cartQtys = 0;
        $cartItems = null;


        // Cart Sort Order: simple, bundle, configurable
        $parentSku = null;
        $totalQuoteItems = 0;

        foreach ($request->getAllItems () as $item)
        {
            $product = $objectProduct->create()->load ($item->getProductId ());
            // Type
            if (!strcmp ($item->getProductType (), 'configurable')
                || !strcmp ($item->getProductType (), 'bundle'))
            {
                $parentSku = $product->getSku ();

                $cartItems [$parentSku] = $item;
                $cartItems [$parentSku]['product'] = $product;

                continue;
            }

            // Configurable
            $heightConfigurable = 0;
            $widthConfigurable  = 0;
            $lengthConfigurable = 0;
            $weightConfigurable = 0;
            $categoriesConfigurable = null;
            $qtyConfigurable = 1;

            if (!empty ($cartItems [$parentSku]))
            {
                $heightConfigurable = $cartItems [$parentSku]['product']->getData($heightAttribute);
                $widthConfigurable  = $cartItems [$parentSku]['product']->getData($widthAttribute);
                $lengthConfigurable = $cartItems [$parentSku]['product']->getData($lengthAttribute);
                $weightConfigurable = $cartItems [$parentSku]->getWeight() / $weightUnit;
                $categoriesConfigurable = $useCategoryAttribute
                    ? $cartItems [$parentSku]['product']->getData($useCategoryAttribute)
                    : $this->_helper->getProductCategories($cartItems [$parentSku]['product'], true)
                ;
                $qtyConfigurable = $cartItems [$parentSku]->getQty ();
            }

            // Simple
            $height = $product->getData($heightAttribute);
            $width = $product->getData($widthAttribute);
            $length = $product->getData($lengthAttribute);
            $weight = $item->getWeight() / $weightUnit; // always kg
            $categories = $useCategoryAttribute
                ? $product->getData($useCategoryAttribute)
                : $this->_helper->getProductCategories($product, false)
            ;

            // Price
            $productPrice = $product->getFinalPrice ();
            $productFinalPrice = 0;
            if (!strcmp ($this->_scopeConfig->getValue ('carriers/intelipost/price_config'), 'product'))
            {
                $productFinalPrice = $this->_helper->haveSpecialPrice ($product) ? $product->getSpecialPrice () : $productPrice;
            }
            else
            {
                $subtotalAmount = $this->_helper->getSubtotalAmount ($productPrice);
                $discountAmount = $this->_helper->getDiscountAmount ();

                $productFinalPrice = (($productPrice / $subtotalAmount) * $discountAmount) + $productPrice;
                if (!$productFinalPrice) $productFinalPrice = floatval($valueOnZero);
            }

            // Data
            $productFinalHeight = $this->_helper->haveData ($height, $heightConfigurable, $heightContingency);
            $productFinalWidth  = $this->_helper->haveData ($width,  $widthConfigurable,  $widthContingency);
            $productFinalLength = $this->_helper->haveData ($length, $lengthConfigurable, $lengthContingency);
            $productFinalWeight = $this->_helper->haveData ($weight, $weightConfigurable, $weightContingency);
            $productFinalCategories = $this->_helper->haveData ($categories, $categoriesConfigurable);

            $productFinalQty = $item->getQty () * $qtyConfigurable;
            $totalQuoteItems += $productFinalQty;
            $cartWeight += $productFinalWeight * $productFinalQty;
            $cartAmount += $productFinalPrice * $productFinalQty;
            $cartQtys   += $productFinalQty;

            $postData ['products'][] = array(
                'weight' => $productFinalWeight,
                'cost_of_goods' => $productFinalPrice,
                'height' => $productFinalHeight,
                'width' => $productFinalWidth,
                'length' => $productFinalLength,
                'quantity' => $productFinalQty,
                'sku_id' => $product->getSku (),
                'id'       => $product->getId(),
                //'product_category' => $productFinalCategories,
                'can_group' => true
            );
        }

        // Additional
        $postData ['additional_information'] = $this->_helper->getAdditionalInformation ($request->getAdditionalInformation());
        $postData ['identification'] = $this->_helper->getPageIdentification ();
        $postData ['cart_weight'] = $cartWeight;
        $postData ['cart_amount'] = $cartAmount;
        $postData ['cart_qtys'] = $cartQtys;
        $postData['seller_id'] = $request->getSellerId() ? $request->getSellerId() : '';

        // Result
        $result = $this->_rateResultFactory->create();

        $resultQuotes = array();

        // API
        try
        {
            $this->logIntelipost('apirequest');

            $response = $this->_api->quoteRequest (\Intelipost\Quote\Helper\Api::POST, \Intelipost\Quote\Helper\Api::QUOTE_BY_PRODUCT, $postData, $api_key);

            $this->logIntelipost('apiresponse');

            $intelipostQuoteId = $response ['content']['id'];
        }
        catch (\Exception $e)
        {
            $error = $this->_rateErrorFactory->create();

            $specificerrmsg = $this->_scopeConfig->getValue ('carriers/intelipost/specificerrmsg');

            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($specificerrmsg ? $specificerrmsg : $e->getMessage ());

            if ($breakOnError) return $error;

            $result->append ($error);

            return $result;
        }

        // Free Shipping
        $this->_helper->checkFreeShipping ($response);

        // Volumes

        /*if($response ['content']['volumes']){
            $volumes = array();
            $volCount = count($response ['content']['volumes']);
            $arrayVol = $this->setProductsQuantity($totalQuoteItems, $volCount);
            $count = 0;
            foreach ($response ['content']['volumes'] as $volume)
            {
                $vWeight = $volume['weight'];
                $vWidth = $volume['width'];
                $vHeight = $volume['height'];
                $vLength = $volume['length'];
                $vProductsQuantity = $arrayVol[$count];

                $aux = array('weight'  => $vWeight,
                    'width'  => $vWidth,
                    'length' => $vLength,
                    'height' => $vHeight,
                    'products_quantity' => $vProductsQuantity);
                array_push($volumes, $aux);
                $count++;
            }
        }*/


        // Methods
        foreach ($response ['content']['delivery_options'] as $child)
        {
            if(!in_array($child['delivery_method_type'], $this->_deliveryMethodType))
            {
                $method = $this->_rateMethodFactory->create();

                // Risk Area
                $deliveryNote = @ $child ['delivery_note'];
                if(!empty($deliveryNote))
                {
                    $error = $this->_rateErrorFactory->create();

                    $riskareamsg = $this->_scopeConfig->getValue ('carriers/intelipost/riskareamsg');

                    $error->setCarrier($this->_code);
                    $error->setCarrierTitle($this->getConfigData('title'));
                    $error->setErrorMessage($riskareamsg ? $riskareamsg : $deliveryNote);

                    $method->setWarnMessage($riskareamsg ? $riskareamsg : $deliveryNote);

                    if ($breakOnError) return $error;

                    $result->append ($error);

                    // continue;
                }

                $method->setScheduled(false);

                // Scheduling
                $child ['available_scheduling_dates'] = null; // new \Zend_Db_Expr('NULL');
                // $schedulingEnabled = @ $child ['scheduling_enabled'];
                $schedulingEnabled = (array_key_exists('scheduling_enabled', $child)) ? $child ['scheduling_enabled'] : false;
                if ($schedulingEnabled)
                {
                    if($calendarOnlyCheckout && strcmp($pageName, 'checkout')) continue;

                    $response = $this->_api->getAvailableSchedulingDates (
                        $originZipcode, $destPostcode, $child ['delivery_method_id']
                    );

                    $child ['available_scheduling_dates'] = json_encode ($response ['content']['available_business_days']);
                }

                // Data
                $deliveryMethodId = $child ['delivery_method_id'];
                $child ['delivery_method_id'] = $this->_code . '_' . $deliveryMethodId;

                // $method = $this->_rateMethodFactory->create();

                $method->setCarrier($this->_code);
                $method->setCarrierTitle($this->getConfigData('title'));

                $method->setMethod($child ['delivery_method_id']);

                $deliveryEstimateBusinessDays = @ $child ['delivery_estimate_business_days'];
                $deliveryEstimateDateExactISO = @ $child ['delivery_estimate_date_exact_iso'];
                if ($estimateDeliveryDate && false /* Disabled */)
                {
                    $response = $this->_api->getEstimateDeliveryDate(
                        $originZipcode, $destPostcode, $child ['delivery_estimate_business_days']
                    );

                    $child ['delivery_estimate_business_days'] = date ('d/m/Y', strtotime ($response ['content']['result_iso']));
                }
                else
                {
                    if ($deliveryEstimateDateExactISO)
                    {
                        $child ['delivery_estimate_business_days'] = date ('d/m/Y', strtotime ($deliveryEstimateDateExactISO));

                        $method->setDeliveryEstimateDateExactIso($deliveryEstimateDateExactISO);
                    }
                }

                $child['delivery_estimate_business_days'] = $estimateDeliveryDate && $deliveryEstimateDateExactISO ? $child ['delivery_estimate_business_days'] : $deliveryEstimateBusinessDays;

                $method->setMethodTitle($this->_helper->getCustomCarrierTitle ($this->_code, $child ['description'], $child ['delivery_estimate_business_days'], $schedulingEnabled));
                $method->setMethodDescription($this->_helper->getCustomCarrierTitle($this->_code, $child['delivery_method_name'], $child['delivery_estimate_business_days'], $schedulingEnabled));

                $method->setDeliveryMethodType($child['delivery_method_type']);

                $child ['delivery_estimate_business_days'] = $deliveryEstimateBusinessDays; // preserve

                $amount = $child ['final_shipping_cost'];
                $cost   = $child ['provider_shipping_cost'];

                $method->setPrice($amount);
                $method->setCost($cost);

                // Save
                //$resultQuotes [] = $this->_helper->saveQuote ($this->_code, $intelipostQuoteId, $child, $postData, $volumes);

                $result->append($method);
            }
            else {

                //save da pickup
                $this->_checkoutSession->setPickupData($child);
            }
        }

        $this->_helper->saveResultQuotes ($resultQuotes, $this->_removeQuotes);

        return $result;
    }

}
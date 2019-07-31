<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block;

class Store extends \Magento\Framework\View\Element\Template
{

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        $this->setTemplate('store.phtml');

        parent::__construct($context);
    }

    public function getAjaxStoreUrl()
    {
        return $this->getUrl('intelipost_pickup/store/index');
    }

    public function getAjaxCitiesUrl()
    {
        return $this->getUrl('intelipost_pickup/pickup/cities');
    }

    public function getAjaxPudoUrl()
    {
        return $this->getUrl('intelipost_pickup/pickup/pudo');
    }

    public function getAjaxSetQuote()
    {
        return $this->getUrl('intelipost_pickup/pickup/setquote');
    }


}


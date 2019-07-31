<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Intelipost\Pickup\Controller\Adminhtml\RegistryConstants;

class GenericButton
{

protected $urlBuilder;

protected $registry;

public function __construct(
    \Magento\Backend\Block\Widget\Context $context,
    \Magento\Framework\Registry $registry
)
{
    $this->urlBuilder = $context->getUrlBuilder();
    $this->registry = $registry;
}

public function getItemId()
{
    return $this->registry->registry(RegistryConstants::CURRENT_INTELIPOST_PICKUP_ITEM_ID);
}

public function getUrl($route = '', $params = [])
{
    return $this->urlBuilder->getUrl($route, $params);
}

}


<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{

public function getButtonData()
{
    $data = [
        'label' => __('Back'),
        'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
        'class' => 'back',
        'sort_order' => 10
    ];

    return $data;
}

public function getBackUrl()
{
    return $this->getUrl('*/*/index');
}

}


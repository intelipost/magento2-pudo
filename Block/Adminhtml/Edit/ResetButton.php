<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton extends GenericButton implements ButtonProviderInterface
{

public function getButtonData()
{
    $data = [
        'label' => __('Reset'),
        'class' => 'reset',
        'id' => 'item-edit-reset-button',
        'on_click' => 'location.reload();',
        'sort_order' => 30
    ];

    return $data;
}

}


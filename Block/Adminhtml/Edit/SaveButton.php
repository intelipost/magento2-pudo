<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{

public function getButtonData()
{
    $data = [
        'label' => __('Save Item'),
        'class' => 'save primary',
        'data_attribute' => [
            'mage-init' => ['button' => ['event' => 'save']],
            'form-role' => 'save',
        ],
        'sort_order' => 90,
    ];

    return $data;
}

}


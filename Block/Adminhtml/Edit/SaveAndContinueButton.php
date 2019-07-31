<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{

public function getButtonData()
{
    $data = [
        'label' => __('Save and Continue Edit'),
        'class' => 'save',
        'data_attribute' => [
            'mage-init' => [
                'button' => ['event' => 'saveAndContinueEdit'],
            ],
        ],
        'sort_order' => 80,
    ];

    return $data;
}

}


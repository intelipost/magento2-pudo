<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{

public function getButtonData()
{
    $itemId = $this->getItemId();
    $data = [];

    if ($itemId)
    {
        $data = [
            'label' => __('Delete Item'),
            'class' => 'delete',
            'id' => 'item-edit-delete-button',
            'data_attribute' => [
                'url' => $this->getDeleteUrl()
            ],
            'on_click' => '',
            'sort_order' => 20,
        ];
    }

    return $data;
}

public function getDeleteUrl()
{
    return $this->getUrl('*/*/delete', ['id' => $this->getItemId()]);
}

}


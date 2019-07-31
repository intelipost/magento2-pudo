<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Displaymode implements OptionSourceInterface
{

public function toOptionArray()
{
    $result = [
        ['value' => 'arrival_date',   'label' => __('Arrival Date')],
        ['value' => 'operation_time', 'label' => __('Operation Time')]
    ];

    return $result;
}

}


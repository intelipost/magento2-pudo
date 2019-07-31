<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Stores implements OptionSourceInterface
{

public function toOptionArray()
{
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $collection = $objectManager->create('Intelipost\Pickup\Model\Resource\Stores\Collection');

    $result = array ();

    foreach ($collection as $child)
    {
        $result [] = ['value' => $child->getId(), 'label' => $child->getName ()];
    }

    return $result;
}

}


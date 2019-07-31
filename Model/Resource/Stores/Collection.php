<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model\Resource\Stores;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

protected function _construct()
{
    $this->_init(
        'Intelipost\Pickup\Model\Stores',
        'Intelipost\Pickup\Model\Resource\Stores'
    );
}

}


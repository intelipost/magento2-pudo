<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Stores extends AbstractDb
{

protected function _construct()
{
    $this->_init('intelipost_pickup_stores', 'entity_id');
}

}


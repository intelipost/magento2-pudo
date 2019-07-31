<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Api\Data;

class StoresResultInterface
{

protected $stores;

/**
 * Get stores list.
 *
 * @api
 * @return \Intelipost\Pickup\Api\Data\StoresResultInterface[]
 */
public function getStores()
{
    return $this->stores;
}

/**
 * Set stores list.
 *
 * @api
 * @param \Intelipost\Pickup\Api\Data\StoresResultInterface[] $stores
 * @return $this
 */
public function setStores(array $stores = null)
{
    $this->stores = $stores;

    return $this;
}

}


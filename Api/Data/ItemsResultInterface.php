<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Api\Data;

class ItemsResultInterface
{

protected $items;

/**
 * Get items list.
 *
 * @api
 * @return \Intelipost\Pickup\Api\Data\itemsResultInterface[]
 */
public function getItems()
{
    return $this->items;
}

/**
 * Set items list.
 *
 * @api
 * @param \Intelipost\Pickup\Api\Data\ItemsResultInterface[] $items
 * @return $this
 */
public function setItems(array $items = null)
{
    $this->items = $items;

    return $this;
}

}


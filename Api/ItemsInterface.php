<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Api;

interface ItemsInterface
{

/**
 * Retrieve items list
 *
 * @api
 * @return \Intelipost\Pickup\Api\Data\ItemsResultInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getList();

/**
 * Retrive item information
 *
 * @api
 * @param int $id
 * @return \Intelipost\Pickup\Api\Data\ItemsInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getInfo($id);

/**
 * Save item information
 *
 * @api
 * @param \Intelipost\Pickup\Api\Data\ItemsInterface[] $items
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function saveItem($items);

/**
 * Delete item
 *
 * @api
 * @param  string $id
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function deleteItem($id);

}


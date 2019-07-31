<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Api;

interface StoresInterface
{

/**
 * Retrieve stores list
 *
 * @api
 * @return \Intelipost\Pickup\Api\Data\StoresResultInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getList();

/**
 * Retrive store information
 *
 * @api
 * @param int $id
 * @return \Intelipost\Pickup\Api\Data\StoresInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getInfo($id);

/**
 * Save store information
 *
 * @api
 * @param \Intelipost\Pickup\Api\Data\StoresInterface[] $stores
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function saveItem($stores);

/**
 * Delete store
 *
 * @api
 * @param  int $id
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function deleteItem($id);

}


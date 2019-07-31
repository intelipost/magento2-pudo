<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Api\Data;

class ItemsInterface
{

/**#@+
 * Constants defined for keys of the data array. Identical to the name of the getter in snake case
 */
const ID = 'id';
const STORE_ID = 'store_id';
const ID_LOJA = 'id_loja';
const DEPARTURE_DATE = 'departure_date';
const ARRIVAL_DATE = 'arrival_date';
const OPERATION_TIME = 'operation_time';
/**#@-*/

protected $id;
protected $idLoja;
protected $storeId;
protected $departureDate;
protected $arrivalDate;
protected $operationTime;

/**
 * Get item id
 *
 * @api
 * @return int|null
 */
public function getId()
{
    return $this->id;
}

/**
 * Set item id
 *
 * @api
 * @param int $id
 * @return $this
 */
public function setId($id)
{
    $this->id = $id;

    return $this;
}

/**
 * Get item store id
 *
 * @api
 * @return int|null
 */
public function getStoreId()
{
    return $this->storeId;
}

/**
 * Set item store id
 *
 * @api
 * @param int $id
 * @return $this
 */
public function setStoreId($id)
{
    $this->storeId = $id;

    return $this;
}

/**
 * Get item id loja
 *
 * @api
 * @return string|null
 */
public function getIdLoja()
{
    return $this->idLoja;
}

/**
 * Set item id loja
 *
 * @api
 * @param string $id
 * @return $this
 */
public function setIdLoja($id)
{
    $this->idLoja = $id;

    return $this;
}

/**
 * Get departure date
 *
 * @api
 * @return string|null
 */
public function getDepartureDate()
{
    return $this->departureDate;
}

/**
 * Set departure date
 *
 * @api
 * @param string $date
 * @return $this
 */
public function setDepartureDate($date)
{
    $this->departureDate = $date;

    return $this;
}

/**
 * Get arrival date
 *
 * @api
 * @return string|null
 */
public function getArrivalDate()
{
    return $this->arrivalDate;
}

/**
 * Set arrival date
 *
 * @api
 * @param string $date
 * @return $this
 */
public function setArrivalDate($date)
{
    $this->arrivalDate = $date;

    return $this;
}

/**
 * Get operation time
 *
 * @api
 * @return int|null
 */
public function getOperationTime()
{
    return $this->operationTime;
}

/**
 * Set operation time
 *
 * @api
 * @param int $time
 * @return $this
 */
public function setOperationTime($time)
{
    $this->operationTime = $time;

    return $this;
}

}


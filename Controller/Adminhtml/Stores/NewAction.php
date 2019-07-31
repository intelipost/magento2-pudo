<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Controller\Adminhtml\Stores;

class NewAction extends \Intelipost\Pickup\Controller\Adminhtml\Stores
{

public function execute()
{
    $resultForward = $this->resultForwardFactory->create();
    $resultForward->forward('edit');

    return $resultForward;
}

}


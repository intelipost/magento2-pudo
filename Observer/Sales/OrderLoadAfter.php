<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Pickup\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderLoadAfter implements ObserverInterface
{

public function execute(Observer $observer)
{
    $order = $observer->getOrder();

    $extensionAttributes = $order->getExtensionAttributes();

    if ($extensionAttributes === null)
    {
        $extensionAttributes = $this->getOrderExtensionDependency();
    }

    $intelipostPickup = $order->getData('intelipost_pickup');

    $extensionAttributes->setIntelipostPickup($intelipostPickup);

    $order->setExtensionAttributes($extensionAttributes);
}

private function getOrderExtensionDependency()
{
    $orderExtension = \Magento\Framework\App\ObjectManager::getInstance()->get(
        '\Magento\Sales\Api\Data\OrderExtension'
    );

    return $orderExtension;
}

}


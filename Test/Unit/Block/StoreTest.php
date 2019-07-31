<?php

namespace Intelipost\Pickup\Test\Unit\Block;

use PHPUnit\Framework\TestCase;
use Intelipost\Pickup\Block\Store;
use Magento\Framework\View\Element\Template\Context;

class StoreTest extends TestCase
{
    private $object;


    protected function setUp()
    {
        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->object = new Store($contextMock);
    }

    public function testStoreInstance()
    {
        $this->assertInstanceOf(Store::class, $this->object);
    }
}
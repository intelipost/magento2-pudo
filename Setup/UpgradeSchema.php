<?php

namespace Intelipost\Pickup\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade (SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) 
        {
            $result = $setup->getConnection()
                ->addColumn(
                    $setup->getTable('intelipost_pickup_stores'),
                    'store_neighborhood',
                    array(
                        'type' => Table::TYPE_TEXT,
                        'comment' => 'Store Neighborhood'
                    )
                );
        }

        $setup->endSetup();
    }
}


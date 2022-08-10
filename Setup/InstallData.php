<?php

namespace Guentur\MageExperiments\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\ResourceConnection;;

class InstallData implements InstallDataInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $resourceTable = $this->resourceConnection->getTableName('guentur_todocrud_todoitem');

        $dataResource = [
            [
                "item_text" => "How to Create SQL Setup Script in Magento 2",
                "is_active" => 1,
            ],
            [
                "item_text" => "In this article, we will find out how to install and upgrade sql script for module in Magento 2. When you install or upgrade a module, you may need to change the database structure or add some new data for current table. To do this, Magento 2 provide you some classes which you can do all of them.",
                "is_active" => 1,
            ],
            [
                "item_text" => '/magento-2-module-development/magento-2-how-to-create-sql-setup-script.html',
                "is_active" => 1,
            ],

        ];

        $setup->getConnection()->insertMultiple($resourceTable, $dataResource);
    }
}

<?php

namespace c\Model\ResourceModel\TodoItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Guentur\MageExperiments\Model\TodoItem','Guentur\MageExperiments\Model\ResourceModel\TodoItem');
    }
}

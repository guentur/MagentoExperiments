<?php

namespace Guentur\MageExperiments\Model\ResourceModel;

class TodoItem extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('guentur_todocrud_todoitem','guentur_todocrud_todoitem_id');
    }
}

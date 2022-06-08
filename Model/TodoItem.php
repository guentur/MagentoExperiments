<?php

namespace Guentur\MageExperiments\Model;

use Guentur\MageExperiments\Api\Data\TodoItemInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class TodoItem extends AbstractModel implements TodoItemInterface, IdentityInterface
{
    const CACHE_TAG = 'guentur_todocrud_todoitem';

    protected function _construct()
    {
        $this->_init('Guentur\MageExperiments\Model\ResourceModel\TodoItem');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}

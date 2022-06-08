<?php

namespace Guentur\MageExperiments\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Guentur\MageExperiments\Model\TodoItemFactory;

class Main extends Template
{
    protected $toDoFactory;

    public function __construct(
        TodoItemFactory $toDoFactory,
        Context $context,
        array $data = []
    ) {
        $this->toDoFactory = $toDoFactory;
        parent::__construct($context, $data);
    }

    function _prepareLayout()
    {
        $todo = $this->toDoFactory->create();

        $todo = $todo->load(1);

        var_dump($todo->getData());

        var_dump($todo->getItemText());

        var_dump($todo->getData('item_text'));
        exit;
    }
}

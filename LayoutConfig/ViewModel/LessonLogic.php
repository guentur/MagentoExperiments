<?php
/**
 * LessonLogic
 *
 */

namespace Guentur\LayoutConfig\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class LessonLogic implements ArgumentInterface
{
    public function getSomeString()
    {
        return 'Some string. You are welcome';
    }
}

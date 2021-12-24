<?php

namespace Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Talexan\Credit\Model\Coin;

class OccasionMap extends AbstractRenderer
{
    /**
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        $row->setOccasion(Coin::getTypes($row->getOccasion()));
        return "{$row->getOccasion()}";
    }
}

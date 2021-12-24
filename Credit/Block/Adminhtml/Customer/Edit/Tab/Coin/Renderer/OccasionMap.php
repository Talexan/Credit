<?php

namespace Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Talexan\Credit\Model\Coin;

class OccasionMap extends AbstractRenderer
{
    /**
     * @var Coin
     */
    protected $coinModel;

    /**
     * @param Context $context
     * @param Coin $coin
     * @param array $data
     */
    public function __construct(
        Context $context,
        Coin $coin,
        array $data = []
    ) {
        $this->coinModel = $coin;

        parent::__construct($context, $data);
    }

    /**
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {//todo
        return $this->coinModel->getTypes($row->getOccasion());
    }
}

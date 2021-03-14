<?php

namespace Talexan\UsersList\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 *
 * @package Talexan\UsersList\Block\Adminhtml\Edit
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init'  => ['button' => ['event' => 'save']],
                'form-role'  => 'save',
            ],
            'sort_order'     => 40,
        ];
    }
}
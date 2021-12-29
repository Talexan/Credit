<?php
namespace Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Registry;
use Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory;

/**
 * Adminhtml loyalty credit coins queue grid block
 *
 * @api
 * @since 100.0.2
 */
class Grid extends Extended
{
    /**
     * Core registry
     *
     * @var Registry|null
     */
    protected $coreRegistry = null;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('coinsHistoryGrid');

        $this->setUseAjax(true);

        $this->setEmptyText(__('No Coins History Found'));
    }

    /**
     * @inheritdoc
     */
    public function getGridUrl()
    {
        return $this->getUrl('credit/ajax/grid', ['_current' => true]);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create()->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', $this->getCurrentCustomerId())
            ->setOrder('created_at', 'desc');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'ids',
            ['header' => __('ID'), 'align' => 'left', 'index' => 'entity_id', 'width' => 10]
        );

        $this->addColumn(
            'occasion',
            [
                'header' => __('Occasion'),
                'type' => 'text',
                'align' => 'center',
                'index' => 'occasion',
                'renderer' => '\Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin\Renderer\OccasionMap',
                'default' => ' ---- '
            ]
        );

        $this->addColumn(
            'coins_received',
            [
                'header' => __('Received coins'),
                'type' => 'text',
                'align' => 'center',
                'index' => 'coins_received',
                'default' => ' ---- '
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created at'),
                'type' => 'datetime',
                'align' => 'center',
                'index' => 'created_at',
                'gmtoffset' => true,
                'default' => ' ---- '
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    private function getCurrentCustomerId(): int
    {
        $customerId = ((int)$this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)) ?:
            (int)$this->getRequest()->getParam('id');
        return $customerId;
    }
}

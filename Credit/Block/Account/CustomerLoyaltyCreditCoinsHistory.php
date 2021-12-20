<?php

namespace Talexan\Credit\Block\Account;

/**
 * Talexan credit history block
 */
class CustomerLoyaltyCreditCoinsHistory extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    // protected $_template = 'Talexan_Credit::customer_coins_history.phtml';

    /**
     * @var \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory
     */
    protected $_coinCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Talexan\Credit\Model\ResourceModel\Coin\Collection
     */
    protected $coins;

    /**
    * Core registry
    *
    * @var \Magento\Framework\Registry
    */
    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coinCollectionFactory = $coinCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_coreRegistry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Coins'));
    }

    /**
     * Get customer coins
     *
     * @return bool|\Talexan\Credit\Model\ResourceModel\Coin\Collection
     */
    public function getCoins()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }

        $this->coins = $this->_coinCollectionFactory->create()->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('created_at', 'desc');

        return $this->coins;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        // Создаю пагинацию для таблицы
        if ($this->getCoins()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'credit.coin.history.pager'
            )->setCollection($this->getCoins());

            // Устанавливаю в макет
            $this->setChild('pager', $pager);
            // Наверное, надо для пагинации.
            // По крайней мере все равно загрузку коллекции придется делять
            $this->getCoins()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get customer account URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }

    /**
     * Get message for no orders.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyCoinsMessage()
    {
        return __('You have placed no coins.');
    }
}

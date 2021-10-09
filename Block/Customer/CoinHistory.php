<?php

namespace Talexant\Credit\Block\Customer;

use \Magento\Framework\App\ObjectManager;

/**
 * Talexan credit history block
 */
class CoinHistory extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Talexan_Credit::customer_coins_history.phtml';

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
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->_coinCollectionFactory = $coinCollectionFactory;
        $this->_customerSession = $customerSession;
        
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
        if (!$this->coins) {
            $this->coins = $this->_coinCollectionFactory->create()->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('created_at', 'desc');
        }
        return $this->coins;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCoins()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class, 'credit.coin.history.pager')
                ->setCollection($this->getCoins());
            $this->setChild('pager', $pager);
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

<?php

namespace Talexan\Credit\Block\Customer\Account\Tab;

/**
 * Talexan credit history block
 */
class LoyaltyCreditCoinsHistory extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory
     */
    protected $coinCollectionFactory;

    /**
     * @var \Talexan\Credit\Model\Coin
     */
    protected $model;

    /**
     * @var \Talexan\Credit\Model\CoinFactory
     */
    protected $modelFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Talexan\Credit\Model\ResourceModel\Coin\Collection
     */
    protected $coins = null;

    /**
     * @var array
     */
    protected $availableLimitsPages;

    /**
     * @var int
     */
    protected $beginPage = 1;

    /**
     * @var int
     */
    protected $currentPage = 0;

    /**
     * @var int
     */
    protected $currentLimitPages = 0;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Theme\Block\Html\Pager $pager
     * @param \Talexan\Credit\Model\CoinFactory $modelFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory $coinCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Theme\Block\Html\Pager $pager,
        \Talexan\Credit\Model\CoinFactory $modelFactory,
        array $data = []
    ) {
        $this->coinCollectionFactory = $coinCollectionFactory;
        $this->customerSession = $customerSession;
        $this->modelFactory = $modelFactory;
        $this->model = $this->modelFactory->create();
        $this->availableLimitsPages = $pager->getAvailableLimit();

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
     * Get customer coins collection
     *
     * @return \Talexan\Credit\Model\ResourceModel\Coin\Collection
     */
    public function getCoinsCollection()
    {
        if (!$this->coins) {
            $this->coins = $this->coinCollectionFactory->create()->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', $this->getCustomerId())
                ->setOrder('created_at', 'desc');
        }

        return $this->coins;
    }

    /**
     * Get current customer id
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customerSession->getCustomerId();
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        // Создаю пагинацию для таблицы
        if ($this->getCoinsCollection()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'credit.coin.history.pager'
            )->setCollection($this->getCoinsCollection());

            // Устанавливаю в макет
            $this->setChild('pager', $pager);
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

    /**
     * @return \Talexan\Credit\Model\Coin
     */
    public function getModel()
    {
        return $this->model;
    }
}

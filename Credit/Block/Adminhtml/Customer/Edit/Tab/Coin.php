<?php
namespace Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class Coin extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * @var string
     */
    //  protected $_template = 'Talexan_Credit::tab/customercoins.phtml';

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $data
     */

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->customerRepository = $customerRepository;
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    private function getCurrentCustomerId(): int
    {
        $customerId = ((int)$this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)) ?:
            (int)$this->getRequest()->getParam('id');
        return $customerId;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Customer Coins');
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Customer Coins');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return (bool)$this->getCurrentCustomerId();
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }
    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        //replace the tab with the url you want
        return $this->getUrl('credit/index/coin', ['_current' => true]);
    }
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return true;
    }
}

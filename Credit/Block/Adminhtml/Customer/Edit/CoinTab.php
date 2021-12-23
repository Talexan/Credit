<?php
namespace Talexan\Credit\Block\Adminhtml\Customer\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class CoinTab extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'Talexan_Credit::tab/customercoins.phtml';

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
        return ''; //$this->getUrl('credit/customer/coin', ['_current' => true]);
    }
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false; //true;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        $this->initForm();

        return $this;
    }

    /**
     * Init form values
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('_customer_coins');
        $this->setForm($form);
        /** @var $fieldset \Magento\Framework\Data\Form\Element\Fieldset */
        $fieldset = $form->addFieldset('coins_fieldset', ['legend' => __('Customer Coins Balance')]);

        $fieldset->addField(
            'amount_coins',
            'text',
            [
                'name' => 'amount_coins',
                'label' => __('Amount of credit coins under the loyalty program '),
                'title' => __('Customer coins'),
                'comment' => __('Amount of credit coins under the loyalty program'),
                'value' => $this->getCustomerCoins(),
                'data-form-part' => $this->getData('target_form'),
            ]
        )->setReadonly(true);

        $fieldset->addField(
            'change_coins',
            'text',
            [
                'name' => 'change_coins',
                'label' => __('Increase the customer\'s account by the specified amount '),
                'title' => __('Change coins'),
                'comment' => __('Increase the customer\'s account by the specified amount'),
                'data-form-part' => $this->getData('target_form'),

            ]
        );

        return $this;
    }

    public function getCustomerCoins()
    {
        /** @var \Magento\Customer\Model\Data\Customer */
        $customerData = $this->customerRepository->getById($this->getCurrentCustomerId());
        return $customerData
            ->getCustomAttribute(\Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE)
            ->getValue();
    }
}

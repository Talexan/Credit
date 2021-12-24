<?php

namespace Talexan\Credit\Block\Adminhtml\Customer\Edit\Tab\Coin;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Customer\Controller\RegistryConstants;

class Form extends Generic
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
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
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**Prepare form fields
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareForm()
    {
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

    /**
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerCoins()
    {
        /** @var \Magento\Customer\Model\Data\Customer */
        $customerData = $this->customerRepository->getById($this->getCurrentCustomerId());
        return $customerData
        ->getCustomAttribute(\Talexan\Credit\Setup\Patch\Data\CustomerCoins::CUSTOMER_ATTRIBUTE_CODE)
        ->getValue();
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    private function getCurrentCustomerId()
    {
        $customerId = ((int)$this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)) ?:
            (int)$this->getRequest()->getParam('id');
        return $customerId;
    }
}

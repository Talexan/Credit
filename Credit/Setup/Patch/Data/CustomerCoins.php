<?php

    namespace Talexan\Credit\Setup\Patch\Data;

    use Magento\Framework\Setup\Patch\DataPatchInterface;
    use Magento\Framework\Setup\Patch\PatchRevertableInterface;cd
    use Magento\Customer\Model\Customer;
 

    /**
     */
    class CustomerCoins implements DataPatchInterface, PatchRevertableInterface
    {
        /**
         * @var \Magento\Framework\Setup\ModuleDataSetupInterface
         */
        private $moduleDataSetup;

         /**
          * Eav setup factory
          * @var \Magento\Eav\Setup\EavSetupFactory
          */
        private $eavSetupFactory;

        /**
         * @var Magento\Eav\Model\Config
         */
        private $eavConfig;

        /**
         * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
         */
        public function __construct(
            \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
            \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
            \Magento\Eav\Model\Config $eavConfig            
        ) {
            /**
             * If before, we pass $setup as argument in install/upgrade function, from now we start
             * inject it with DI. If you want to use setup, you can inject it, with the same way as here
             */
            $this->moduleDataSetup = $moduleDataSetup;
            $this->eavSetupFactory = $eavSetupFactory;
            $this->eavConfig = $eavConfig;
        }

        /**
         * {@inheritdoc}
         */
        public function apply()
        {
            $this->moduleDataSetup->getConnection()->startSetup();
            //The code that you want apply in the patch
            //Please note, that one patch is responsible only for one setup version
            //So one UpgradeData can consist of few data patches

            $eavSetup = $this->eavSetupFactory->create(
                                    ['setup' => $this->moduleDataSetup]);
                                    $eavSetup->addAttribute(
                                        Customer::ENTITY,
                                        'customer_coins',
                                        [
                                            'type'         => 'int',
                                            'label'        => 'Amount of coins',
                                            'input'        => 'text',
                                            'required'     => false,
                                            'visible'      => true,
                                            'user_defined' => true,
                                            'position'     => 999,
                                            'system'       => 0,
                                        ]
                                    );
                                    $coinsAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'customer_coins');
                            
                                    // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
                                    $coinsAttribute->setData(
                                        'used_in_forms',
                                        ['adminhtml_customer']
                            
                                    );
                                    $coinsAttribute->save();

            $this->moduleDataSetup->getConnection()->endSetup();
        }

        /**
         * {@inheritdoc}
         */
        public static function getDependencies()
        {
            /**
             * This is dependency to another patch. Dependency should be applied first
             * One patch can have few dependencies
             * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
             * versions, right now you need to point from patch with higher version to patch with lower version
             * But please, note, that some of your patches can be independent and can be installed in any sequence
             * So use dependencies only if this important for you
             */
            return []; // No dependencies
        }

        public function revert()
        {
            $this->moduleDataSetup->getConnection()->startSetup();
            //Here should go code that will revert all operations from `apply` method
            //Please note, that some operations, like removing data from column, that is in role of foreign key reference
            //is dangerous, because it can trigger ON DELETE statement
            
            $eavSetup = $this->eavSetupFactory->create();
            $eavSetup->removeAttribute(Customer::ENTITY, 'customer_coins');
            
            
            $this->moduleDataSetup->getConnection()->endSetup();
        }

        /**
         * {@inheritdoc}
         */
        public function getAliases()
        {
            /**
             * This internal Magento method, that means that some patches with time can change their names,
             * but changing name should not affect installation process, that's why if we will change name of the patch
             * we will add alias here
             */
            return [];
        }

        /**
         * {@inheritdoc}
         */ 
        public static function  getVersion( ) 
        { 
            return  '1.0.0'; 
        } 
    }
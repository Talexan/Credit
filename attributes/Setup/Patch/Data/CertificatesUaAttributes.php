<?php

    namespace Talexan\Attributes\Setup\Patch\Data;

    use Magento\Framework\Setup\Patch\DataPatchInterface;
    use Magento\Framework\Setup\Patch\PatchRevertableInterface;
 

    /**
     */
    class CertificatesUaAttributes implements DataPatchInterface, PatchRevertableInterface
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
         * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
         */
        public function __construct(
            \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
            \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory            
        ) {
            /**
             * If before, we pass $setup as argument in install/upgrade function, from now we start
             * inject it with DI. If you want to use setup, you can inject it, with the same way as here
             */
            $this->moduleDataSetup = $moduleDataSetup;
            $this->eavSetupFactory = $eavSetupFactory;
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
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY,
                'certificates_ukr',
            [
                'group' => 'Product Details',
                'type' => 'text',
                'label' => 'Certificates of Ukraine',
                'input' => 'text',
                'frontend' => 'Talexan\Attributes\Model\Attribute\Frontend\Certificates',
                'backend' => 'Talexan\Attributes\Model\Attribute\Backend\Certificates',
                'required' => false,
                'default' => 'ISO9001, ISO14001, ISO22000',
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'visible' => true,
                'is_html_allowed_on_front' => true,
                'visible_on_front' => false,
                'apply_to' => 'simple,groped,configurable,virtual,bundle,downloadable',
                'attribute_set' => 'Bottom'
            ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY,
                'is_enabled_my_att_show',
            [
                    'group' => 'Product Details',
                    'type' => 'int',
                    'backend' => \Talexan\Attributes\Model\Attribute\Backend\MyAttEnable::class,
                    'frontend' => \Talexan\Attributes\Model\Attribute\Frontend\MyAttEnable::class,
                    'label' => 'Enabled Show Certificates of Ukraine',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'required' => true,
                    'sort_order' => 51,
                    'user_defined' => false,
                    'default' => '1',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'unique' => false,
                    'apply_to' => 'simple,configurable,virtual,bundle,downloadable',
                    'attribute_set' => 'Bottom'
            ]);

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
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'certificates_ukr');
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'is_enabled_my_att_show');
            
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
            return  '2.0.0'; 
        } 
    }
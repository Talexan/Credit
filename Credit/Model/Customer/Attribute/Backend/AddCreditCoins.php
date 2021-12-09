<?php
namespace Talexan\Credit\Model\Customer\Attribute\Backend;

class AddCreditCoins extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    public function validate($object)
    {
        $attribute_code = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attribute_code);

        if ($value == 'test') {
            throw new \Magento\Framework\Exception\LocalizedException(__("Value can't be test"));
        }

        return true;
    }
}
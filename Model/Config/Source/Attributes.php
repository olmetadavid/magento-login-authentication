<?php

class Clever_LoginAuth_Model_Config_Source_Attributes
{

    public function toOptionArray()
    {
        // Get customer attributes.
        $attributes = Mage::getModel('customer/entity_attribute_collection');

        // Initialize results.
        $results = array(
            0 => Mage::helper('clever_loginauth')->__('-- Standard Authentication --'),
        );

        foreach ($attributes as $attribute) {

            // Add attribute to results.
            if (($label = $attribute->getFrontendLabel())) {
                $results[$attribute->getAttributeCode()] = $label;
            }
        }

        return $results;
    }

}
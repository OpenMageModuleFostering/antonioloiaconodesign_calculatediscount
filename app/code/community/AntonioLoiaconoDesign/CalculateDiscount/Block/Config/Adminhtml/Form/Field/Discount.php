<?php
class AntonioLoiaconoDesign_CalculateDiscount_Block_Config_Adminhtml_Form_Field_Discount
    extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
		// specify the attribute code
		$discount = Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/attributeofdiscount');
		$config    = Mage::getModel('eav/config');
		$attribute = $config->getAttribute(Mage_Catalog_Model_Product::ENTITY, $discount);
		$options = $attribute->getSource()->getAllOptions();
        foreach ($options as $option) {
            $this->addOption($option['value'], $option['label']);
        }
        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
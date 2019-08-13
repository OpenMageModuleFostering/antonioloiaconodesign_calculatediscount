<?php
class AntonioLoiaconoDesign_CalculateDiscount_Block_Config_DiscountConditions
extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemRenderer;

    public function _prepareToRender()
    {
		$discount = ucfirst(str_replace('_',' ', Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/attributeofdiscount')));
        $this->addColumn('discount_option', array(
            'label' => Mage::helper('calculatediscount')->__(($discount != '' ? $discount . ' Option'  :  'Attribute Option')),
            'renderer' => $this->_getRenderer(),
        ));
        $this->addColumn('from_percent', array(
            'label' => Mage::helper('calculatediscount')->__('From %'),
            'style' => 'width:100px',
        ));
        $this->addColumn('to_percent', array(
            'label' => Mage::helper('calculatediscount')->__('To %'),
            'style' => 'width:100px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('calculatediscount')->__('Add Condition');
    }

    protected function  _getRenderer() 
    {
        if (!$this->_itemRenderer) {
            $this->_itemRenderer = $this->getLayout()->createBlock(
                'calculatediscount/config_adminhtml_form_field_discount', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getRenderer()
                ->calcOptionHash($row->getData('discount_option')),
            'selected="selected"'
        );
    }
}
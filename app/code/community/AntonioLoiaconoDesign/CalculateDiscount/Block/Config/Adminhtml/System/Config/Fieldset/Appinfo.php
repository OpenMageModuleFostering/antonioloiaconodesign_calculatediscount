<?php

class AntonioLoiaconoDesign_CalculateDiscount_Block_Config_Adminhtml_System_Config_Fieldset_Appinfo 
	extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

		protected $_template = 'calculatediscount/system/config/fieldset/appinfo.phtml';

		public function render(Varien_Data_Form_Element_Abstract $element) {
        	return $this->toHtml();
    	}

    	protected function getModuleVersion() {
        	return (string) Mage::getConfig()->getNode('modules/AntonioLoiaconoDesign_CalculateDiscount/version');
    	}

    	protected function getAldWebsite() {
        	return (string) "http://www.antonioloiaconodesign.it";
    	}

    	protected function getCalculateDiscountInfo() {
        	return (string) "http://www.antonioloiaconodesign.it/calculate-discount";
    	}
	}

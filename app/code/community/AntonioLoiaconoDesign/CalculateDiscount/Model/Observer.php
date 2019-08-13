<?php

class AntonioLoiaconoDesign_CalculateDiscount_Model_Observer
{
    /**
     * Add a new massaction to export products to CSV
     * @param Varien_Event_Observer $observer
     */
    public function addMassCalculate(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        
        $block->getMassactionBlock()->addItem('calculatediscount', array(
            'label' => 'Calculate Discount',
            'confirm' => $block->__('Are you sure you want to calculates discount of the selected listing(s)?'),
            'url' => $block->getUrl('adminhtml/calculatediscount/massCalculate', array(
            'store' => Mage::app()->getRequest()->getParam('store')
            ))
        ));
    }
    
    public function calculateDiscountAfterProductSave($observer)
    {
        $product           = $observer->getProduct();
        $discountAttribute = Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/attributeofdiscount');
        $Price			   = $product->getPrice();
		$finalPrice 	       = $product->getFinalPrice();
					
		if (Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/producttaxclass')) {
			$Price			   = Mage::helper('core')->currency($product->getPrice(),true,false);
			$finalPrice 	   = Mage::helper('core')->currency($product->getFinalPrice(),true,false);
		}
					
        if ($finalPrice < $Price && $product->getVisibility() != Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE){
			$discountPer        = round(($Price - $finalPrice) / $Price * 100, 0, PHP_ROUND_HALF_DOWN);
            $discountConditions = Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/discountconditions');
			
            if ($discountConditions) {
                $discountConditions = unserialize($discountConditions);
                if (is_array($discountConditions)) {
                    
                    foreach ($discountConditions as $discountConditionsRow) {
                        $discountOption = $discountConditionsRow['discount_option'];
                        $fromPercent    = $discountConditionsRow['from_percent'];
                        $toPercent      = $discountConditionsRow['to_percent'];
                        
                        if (filter_var($discountPer, FILTER_VALIDATE_INT, array(
                            "options" => array(
                                "min_range" => $fromPercent,
                                "max_range" => $toPercent
                            )
                        )) === false) {
                            continue;
                        } else {
                            Mage::getSingleton('catalog/product_action')->updateAttributes(array(
                                $product->getId()
                            ), array(
                                $discountAttribute => $discountOption
                            ), 0);
                        }
                        
                    }
                }
                
            }
            
        }else{
						Mage::getSingleton('catalog/product_action')->updateAttributes(array(
                        $product->getId()
                        ), array(
                        $discountAttribute => ''
                        ), 0);
						
						}
        
    }
    
}

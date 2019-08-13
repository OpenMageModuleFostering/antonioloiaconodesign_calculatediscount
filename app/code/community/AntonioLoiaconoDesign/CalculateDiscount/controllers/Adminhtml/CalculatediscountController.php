<?php
class AntonioLoiaconoDesign_CalculateDiscount_Adminhtml_CalculatediscountController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/products');
    }
    
    public function massCalculateAction()
    {
        $productIds = $this->getRequest()->getParam('product');
        $store      = $this->_getStore();
        if (!is_array($productIds)) {
            $this->_getSession()->addError($this->__('Please select product(s).'));
            $this->_redirect('adminhtml/catalog_product/index');
        } else {
            try {
                $collection = Mage::getResourceModel('catalog/product_collection')->addFieldToFilter('entity_id', array(
                    $productIds
                ))->addAttributeToSelect('*');
                
                foreach ($collection as $_product) {
                    
                    $product           = Mage::getModel('catalog/product')->load($_product->getId());
                    $discountAttribute = Mage::getStoreConfig('antonioloiaconodesign_calculatediscount/general/attributeofdiscount');
                    
                    if ($product->getFinalPrice() < $product->getPrice()) {
                        
                        $discountPer        = floor(100 - ($product->getFinalPrice() / $product->getPrice()) * 100);
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
                                            $discountAttribute => $discountOption['value']
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
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('adminhtml/catalog_product/index');
            }
            
            $this->_getSession()->addSuccess($this->__('It was calculated the discount of %d product(s).', count($collection)));
            $this->_redirect('adminhtml/catalog_product/index');
            
        }
        
    }
    
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
}
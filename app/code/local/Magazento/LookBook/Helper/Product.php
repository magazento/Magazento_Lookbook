<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author kurisu
 */
class Magazento_LookBook_Helper_Product extends Mage_Catalog_Helper_Product {

    public function initProduct($productId, $controller, $params = null) {
        // Prepare data for routine
        if (!$params) {
            $params = new Varien_Object();
        }

        // Init and load product
        Mage::dispatchEvent('catalog_controller_product_init_before', array(
            'controller_action' => $controller,
            'params' => $params,
        ));

        if (!$productId) {
            return false;
        }
       
        
        $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

        if (!$this->canShow($product)) {
         //   var_dump('FALSE');
            return false;
        }
        if (!in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
            return false;
        }
       
        Mage::unregister('current_product');
        Mage::unregister('product');
        Mage::register('current_product', $product);
        Mage::register('product', $product);
        try {
            Mage::dispatchEvent('catalog_controller_product_init', array('product' => $product));
            Mage::dispatchEvent('catalog_controller_product_init_after', array('product' => $product,
                'controller_action' => $controller
                    )
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $product;
    }
}

?>

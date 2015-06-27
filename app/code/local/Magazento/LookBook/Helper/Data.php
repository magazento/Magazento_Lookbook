<?php

class Magazento_LookBook_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Prepares product view page - inits layout and all needed stuff
     *
     * $params can have all values as $params in Mage_Catalog_Helper_Product - initProduct().
     * Plus following keys:
     *   - 'buy_request' - Varien_Object holding buyRequest to configure product
     *   - 'specify_options' - boolean, whether to show 'Specify options' message
     *   - 'configure_mode' - boolean, whether we're in Configure-mode to edit product configuration
     *
     * @param int $productId
     * @param Mage_Core_Controller_Front_Action $controller
     * @param null|Varien_Object $params
     *
     * @return Mage_Catalog_Helper_Product_View
     */
    public function prepareAndRender($productIds, $controller, $params = null) {
        foreach ($productIds as $productId) {
            // Prepare data

            $productHelper = Mage::helper('lookbook/product');
            if (!$params) {
                $params = new Varien_Object();
            }

            // Standard algorithm to prepare and rendern product view page
            $product = $productHelper->initProduct($productId, $controller, $params);
            if ($product) {
         //       throw new Mage_Core_Exception($this->__('Product is not loaded'), $this->ERR_NO_PRODUCT_LOADED);
         //   }

            $buyRequest = $params->getBuyRequest();
            if ($buyRequest) {
                $productHelper->prepareProductOptions($product, $buyRequest);
            }

            if ($params->hasConfigureMode()) {
                $product->setConfigureMode($params->getConfigureMode());
            }

            Mage::dispatchEvent('catalog_controller_product_view', array('product' => $product));

            if ($params->getSpecifyOptions()) {
                $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
                Mage::getSingleton('catalog/session')->addNotice($notice);
            }


            $this->initProductLayout($product, $controller);
            }
        }
        $controller->initLayoutMessages(array('catalog/session', 'tag/session', 'checkout/session'))
                ->renderLayout();

        return $this;
    }

    /**
     * Inits layout for viewing product page
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Controller_Front_Action $controller
     *
     * @return Mage_Catalog_Helper_Product_View
     */
    public function initProductLayout($product, $controller) {
        $design = Mage::getSingleton('catalog/design');
        $settings = $design->getDesignSettings($product);

        if ($settings->getCustomDesign()) {
            $design->applyCustomDesign($settings->getCustomDesign());
        }

        $update = $controller->getLayout()->getUpdate();
        $update->addHandle('default');
        $controller->addActionLayoutHandles();

//       $update->addHandle('PRODUCT_TYPE_' . $product->getTypeId());
//        $update->addHandle('PRODUCT_' . $product->getId());
        $controller->loadLayoutUpdates();

        // Apply custom layout update once layout is loaded
        $layoutUpdates = $settings->getLayoutUpdates();
        if ($layoutUpdates) {
            if (is_array($layoutUpdates)) {
                foreach ($layoutUpdates as $layoutUpdate) {
                    $update->addUpdate($layoutUpdate);
                }
            }
        }

        $controller->generateLayoutXml()->generateLayoutBlocks();

        // Apply custom layout (page) template once the blocks are generated
//        if ($settings->getPageLayout()) {
//            $controller->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
//        }

        return $this;
    }

}
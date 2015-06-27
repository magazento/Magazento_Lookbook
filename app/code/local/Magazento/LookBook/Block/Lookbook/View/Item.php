<?php

class Magazento_Lookbook_Block_Lookbook_View_Item extends Mage_Catalog_Block_Product_View //Mage_Catalog_Block_Product_Abstract 
{

    protected $_look;
    protected $_current_product;
    protected $_isGalleryDisabled;

    /**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getGalleryImages() {
        if ($this->_isGalleryDisabled) {
            return array();
        }
        $collection = $this->getProduct()->getMediaGalleryImages();
        return $collection;
    }

    /**
     * Retrieve gallery url
     *
     * @param null|Varien_Object $image
     * @return string
     */
    public function getGalleryUrl($image = null) {
        $params = array('id' => $this->getProduct()->getId());
        if ($image) {
            $params['image'] = $image->getValueId();
        }
        return $this->getUrl('catalog/product/gallery', $params);
    }

    /**
     * Disable gallery
     */
    public function disableGallery() {
        $this->_isGalleryDisabled = true;
    }
    public function getLook() {
        if (!isset($this->_look)) {
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('lookbook/lookbook')->load($id);
            return $model;
        }
        else
            return $this->_look;
    }
//    public function getProduct(){
//        
//           return $this->getData('product');
//        
//        }
        public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', Mage::registry('product'));
        }
        return $this->getData('product');
    }
   
    public function setProduct(Mage_Catalog_Model_Product $product){
         $this->setData('product', $product);
        $this->_current_product = $product;
    }
 protected function _prepareLayout() {
    
 }
    
}

?>

<?php

class Magazento_LookBook_Model_Lookbook extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('lookbook/lookbook');
    }

    public function getImage(){
        
    }

    public function getLookbookProducts() {
        $collection = Mage::getResourceModel('lookbook/lookbook_products_collection')
                ->addFieldToFilter('lookbook_id', $this->getId());
        $result = array();
        foreach ($collection as $value) {
            $result[$value['product_id']] = array('position' => $value['position']);
        }
        return $result;
    }

    public function getProducts() {

        $collection = Mage::getResourceModel('lookbook/lookbook_products_collection')
                ->addFieldToFilter('lookbook_id', $this->getId())
                ->setOrder('sort_order','asc');
        $result = array();

        foreach ($collection as $product) {
            //  var_dump($product['product_id']);
            $result[] = Mage::getModel('catalog/product')->load($product['product_id']);
        }
        return $result;
    }
    
    public function getMainImage(){
     //   var_dump($this->getResource()->getMainImage($this));
        $result=Mage::getResourceModel('lookbook/lookbook')->getMainImage($this);
       // var_dump($result);
        return $result['file'];
    }
}
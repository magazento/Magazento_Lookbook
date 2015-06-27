<?php

class Magazento_Lookbook_Block_Lookbook_View_Item_Media extends Magazento_Lookbook_Block_Lookbook_View_Item//Mage_Catalog_Block_Product_View_Media
{
//    public function getProduct() {
//        var_dump (Magazento_Lookbook_Block_Lookbook_View_Item::getProduct());
//    }
//    public function __construct() {
//        var_dump('in media');
//        parent::__construct();
//    }
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
    public function getProduct() {
        //$product=parent::getProduct();
        return $this->getData('product');
    }
}

?>

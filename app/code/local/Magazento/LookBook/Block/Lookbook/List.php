<?php

class Magazento_Lookbook_Block_Lookbook_List extends Mage_Catalog_Block_Product_Abstract {

    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection() {
        if (is_null($this->_productCollection)) {
            $collection = Mage::getModel('lookbook/lookbook')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore(true)->getId())
                    ->addfieldtofilter('from_date', 
                             array(
                                 array('to' => Mage::getModel('core/date')->gmtDate()),
                                 array('from_date', 'null'=>'')))
                   ->addfieldtofilter('to_date',
                             array(
                                 array('gteq' => Mage::getModel('core/date')->gmtDate()),
                                 array('to_date', 'null'=>''))
                                  );
            
            $this->_productCollection = $collection;
        }

        return $this->_productCollection;
    }

    /**
     * Get catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer() {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection() {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
//    public function getMode()
//    {
//        return $this->getChild('toolbar')->getCurrentMode();
//    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml() {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // set collection to toolbar and apply sort
//        $toolbar->setCollection($collection);
//        $this->setChild('toolbar', $toolbar);
//        Mage::dispatchEvent('catalog_block_product_list_collection', array(
//            'collection' => $this->_getProductCollection()
//        ));

        $this->_getProductCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock() {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml() {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    public function setCollection($collection) {
        $this->_productCollection = $collection;
        return $this;
    }

    public function addAttribute($code) {
        $this->_getProductCollection()->addAttributeToSelect($code);
        return $this;
    }

    public function getPriceBlockTemplate() {
        return $this->_getData('price_block_template');
    }

    /**
     * Retrieve Catalog Config object
     *
     * @return Mage_Catalog_Model_Config
     */
    protected function _getConfig() {
        return Mage::getSingleton('catalog/config');
    }

}

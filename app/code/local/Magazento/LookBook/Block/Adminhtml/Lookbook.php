<?php
class Magazento_LookBook_Block_Adminhtml_Lookbook extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_lookbook';
    $this->_blockGroup = 'lookbook';
    $this->_headerText = Mage::helper('lookbook')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('lookbook')->__('Add Item');
    parent::__construct();
  }
//  protected function _prepareLayout() {
//        parent::_prepareLayout();
//       
//            if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
//                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
//
//                $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/variables.js');
//                $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/wysiwyg/widget.js');
//                $this->getLayout()->getBlock('head')->addJs('lib/flex.js');
//                $this->getLayout()->getBlock('head')->addJs('lib/FABridge.js');
//                $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/flexuploader.js');
//                $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/browser.js');
//                $this->getLayout()->getBlock('head')->addJs('extjs/ext-tree.js');
//                $this->getLayout()->getBlock('head')->addJs('extjs/ext-tree-checkbox.js');
//
//                $this->getLayout()->getBlock('head')->addItem('js_css', 'extjs/resources/css/ext-all.css');
//                $this->getLayout()->getBlock('head')->addItem('js_css', 'extjs/resources/css/ytheme-magento.css');
//                $this->getLayout()->getBlock('head')->addItem('js_css', 'prototype/windows/themes/default.css');
//                $this->getLayout()->getBlock('head')->addItem('js_css', 'prototype/windows/themes/magento.css');
//            }
//        
//    }
}
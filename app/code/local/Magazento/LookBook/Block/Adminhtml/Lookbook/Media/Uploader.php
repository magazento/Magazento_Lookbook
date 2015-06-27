<?php

class Magazento_Lookbook_Block_Adminhtml_Lookbook_Media_Uploader extends Mage_Adminhtml_Block_Media_Uploader {

    public function __construct() {
        parent::__construct();
   //     $this->setTemplate("magazento_lookbook/uploader.phtml");
    }

    public function getUploadButtonHtml() {
        return $this->getChildHtml('upload_button');
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    }

}

?>

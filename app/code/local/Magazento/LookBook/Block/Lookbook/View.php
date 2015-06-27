<?php

class Magazento_Lookbook_Block_Lookbook_View extends Mage_Core_Block_Template {
   
    public function getProductListHtml()
    {
        return $this->getChildHtml('list');
    }

 
}
?>

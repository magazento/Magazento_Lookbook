<?php

class Magazento_LookBook_Model_Lookbook_Products extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('lookbook/lookbook_products');
    }

}

?>

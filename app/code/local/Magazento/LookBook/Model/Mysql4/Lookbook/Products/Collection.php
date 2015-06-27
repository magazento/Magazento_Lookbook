<?php

class Magazento_LookBook_Model_Mysql4_Lookbook_Products_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('lookbook/lookbook_products');
    }

}

?>

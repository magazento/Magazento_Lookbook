<?php

class Magazento_LookBook_Model_Mysql4_Lookbook_Products extends Mage_Core_Model_Mysql4_Abstract{
     public function _construct() {
        // Note that the lookbook_id refers to the key field in your database table.
        $this->_init('lookbook/lookbook_products', 'lookbook_entity_id');
    }
}

?>

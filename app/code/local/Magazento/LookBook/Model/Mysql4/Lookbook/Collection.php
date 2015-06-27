<?php

class Magazento_LookBook_Model_Mysql4_Lookbook_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lookbook/lookbook');
    }
     /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Magazento_Lookbook_Model_Mysql4_Lookbook_Collection
     */
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->join(
                    array('store_table' => $this->getTable('lookbook/lookbook_stores')),
                    'main_table.lookbook_id = store_table.lookbook_id',
                    array()
                    )
                    ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }
    public function addMainImagesData() {
          $this->getSelect('*')->joinleft(
                    array('images_table' => $this->getTable('lookbook/lookbook_images')),
                    'main_table.lookbook_id = images_table.lookbook_id',
                    array('images_table.file')
                    )
                  ->where('images_table.is_main = 1 or images_table.is_main is null');
          
          return $this;
    }

}
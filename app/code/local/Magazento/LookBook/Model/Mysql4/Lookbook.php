<?php

class Magazento_LookBook_Model_Mysql4_Lookbook extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the lookbook_id refers to the key field in your database table.
        $this->_init('lookbook/lookbook', 'lookbook_id');
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        if ($object->getData('links')) {

            foreach (Mage::getResourceModel('lookbook/lookbook_products_collection')
                    ->addFieldToFilter('lookbook_id', $object->getId()) as $product) {
                $product->delete();
            }
            $data = $object->getData('links');

            $link_Data = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['prodlist']);

            foreach ($link_Data as $key => $value) {
                $prodlistModel = Mage::getModel('lookbook/lookbook_products');
                $prodlistModel->setLookbookId($object->getId())
                        ->setProductId($key)
                        ->setSortOrder($value['sort_order']);

                $prodlistModel->save();
            }
        }
        if ($object->getData('images')) {
            $this->__saveToImageTable($object);
        }
         $this->__saveToStoreTable($object);
        return parent::_afterSave($object);
    }

    private function __saveToImageTable(Mage_Core_Model_Abstract $object) {
        if ($_imageList = $object->getData('images')) {
            $_imageList = Zend_Json::decode($_imageList);

            if (is_array($_imageList) and sizeof($_imageList) > 0) {
                $_imageTable = $this->getTable('lookbook/lookbook_images');
                $_adapter = $this->_getWriteAdapter();
                $_adapter->beginTransaction();
                try {
                    $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
                    $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_images'), $condition);

                    foreach ($_imageList as &$_item) {
                        if (isset($_item['removed']) and $_item['removed'] == '1') {
                            $_adapter->delete($_imageTable, $_adapter->quoteInto('image_id = ?', $_item['value_id'], 'INTEGER'));
                        } else {
                            $main=0;
                            if ($object->getData('is_main')==$_item['file']) {$main=1;}
                            $_data = array(
                                'label' => $_item['label'],
                                'file' => $_item['file'],
                                'position' => $_item['position'],
                                'disabled' => $_item['disabled'],
                                'is_main' => $main,
                                'lookbook_id' => $object->getId());

                            $_adapter->insert($_imageTable, $_data);
                        }
                    }
                    $_adapter->commit();
                } catch (Exception $e) {
                    $_adapter->rollBack();
                  //  echo $e->getMessage();
                }
            }
        }
    }
    /**
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassDelete()) {
            $object = $this->__loadStore($object);
            $object = $this->loadImages($object);
        }

        return parent::_afterLoad($object);
    }
    public function loadImages(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('lookbook/lookbook_images'))
                ->where('lookbook_id = ?', $object->getId())
                ->order(array('position ASC', 'label'));
        $object->setData('image', $this->_getReadAdapter()->fetchAll($select));
        
        return $object;
    }
 /**
     * Save stores
     */
    private function __saveToStoreTable(Mage_Core_Model_Abstract $object) {
        if (!$object->getData('stores')) {
            $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);

            $storeArray = array(
                'lookbook_id' => $object->getId(),
                'store_id' => '0');
            $this->_getWriteAdapter()->insert(
                    $this->getTable('lookbook/lookbook_stores'), $storeArray);
            return true;
        }

        $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);
        foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array(
                'lookbook_id' => $object->getId(),
                'store_id' => $store);
            $this->_getWriteAdapter()->insert(
                    $this->getTable('lookbook/lookbook_stores'), $storeArray);
        }
    }
    /**
     * Load stores
     */
    private function __loadStore(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('lookbook/lookbook_stores'))
                ->where('lookbook_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('store_id', $array);
        }
        return $object;
    }
    public function getMainImage($object){
        $select = $this->_getReadAdapter()->select('*')
                ->from($this->getTable('lookbook/lookbook_images'))
                ->where('is_main=1 and lookbook_id = ?', $object->getId());
                
       $image_data=$this->_getReadAdapter()->fetchRow($select);
      
       return $image_data;
    }
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        
        $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_images'), $condition);
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_products'), $condition);

       
    }
}
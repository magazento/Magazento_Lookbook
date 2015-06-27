<?php

//require_once('Mage/Catalog/controllers/ProductController.php');
class Magazento_LookBook_IndexController extends Mage_Core_Controller_Front_Action {//Mage_Catalog_ProductController {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
//    parent::indexAction();
    }

    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
      
        $model = Mage::getModel('lookbook/lookbook')->load($id);
        if ($model->getId() || $id == 0) {
            $viewHelper = Mage::helper('lookbook/data');
            //    foreach (array_keys($model->getLookbookProducts()) as $productId)
            $viewHelper->prepareAndRender(array_keys($model->getLookbookProducts()), $this);
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

}
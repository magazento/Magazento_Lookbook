<?php

class Magazento_LookBook_Adminhtml_LookbookController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('lookbook/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
//        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
//            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
           $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
//        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

            $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/variables.js');
            $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/wysiwyg/widget.js');
            $this->getLayout()->getBlock('head')->addJs('lib/flex.js');
            $this->getLayout()->getBlock('head')->addJs('lib/FABridge.js');
            $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/flexuploader.js');
            $this->getLayout()->getBlock('head')->addJs('mage/adminhtml/browser.js');
            $this->getLayout()->getBlock('head')->addJs('extjs/ext-tree.js');
            $this->getLayout()->getBlock('head')->addJs('extjs/ext-tree-checkbox.js');

            $this->getLayout()->getBlock('head')->addItem('js_css', 'extjs/resources/css/ext-all.css');
            $this->getLayout()->getBlock('head')->addItem('js_css', 'extjs/resources/css/ytheme-magento.css');
            $this->getLayout()->getBlock('head')->addItem('js_css', 'prototype/windows/themes/default.css');
            $this->getLayout()->getBlock('head')->addItem('js_css', 'prototype/windows/themes/magento.css');
        }
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function galleryAction() {
        //   
        $this->_initAction();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lookbook/lookbook')->load($id);
        Mage::register('current_lookbook', $model);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('lookbook_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('lookbook/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit'))
                    ->_addLeft($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'lookbook' . DS;
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['image'] = $_FILES['image']['name'];
            }


            $model = Mage::getModel('lookbook/lookbook');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                               $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lookbook')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('lookbook/lookbook');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if (!is_array($lookbookIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getModel('lookbook/lookbook')->load($lookbookId);
                    $lookbook->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($lookbookIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if (!is_array($lookbookIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getSingleton('lookbook/lookbook')
                            ->load($lookbookId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($lookbookIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'lookbook.csv';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_lookbook_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'lookbook.xml';
        $content = $this->getLayout()->createBlock('lookbook/adminhtml_lookbook_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    public function productsAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }

    public function productsgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('products.grid')
                ->setProdlist($this->getRequest()->getPost('products_prodlist', null));
        $this->renderLayout();
    }

    public function imageAction() {
        $result = array();
        try {
            $uploader = new Varien_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
//             $path = Mage::getBaseDir('media') . DS . 'lookbook' . DS;
//             $result =  $uploader->save($path);
            $result = $uploader->save(
                    Mage::getSingleton('lookbook/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('lookbook/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                'name' => session_name(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

}
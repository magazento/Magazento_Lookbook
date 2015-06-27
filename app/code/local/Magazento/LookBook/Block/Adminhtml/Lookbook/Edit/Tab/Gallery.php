<?php
/**
 * Description of Gallery
 *
 * @author kurisu
 */
class Magazento_Lookbook_Block_Adminhtml_Lookbook_Edit_Tab_Gallery extends Mage_Adminhtml_Block_Widget {

    public function __construct() {
        parent::__construct();
        $this->setTemplate("magazento_lookbook/edit/tab/image.phtml");
        $this->setId("media_gallery_content");
        $this->setHtmlId("media_gallery_content");
    }

    protected function _prepareForm() {
        $data = $this->getRequest()->getPost();
        $form = new Varien_Data_Form();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _prepareLayout() {
        $this->setChild("uploader", $this->getLayout()->createBlock("lookbook/adminhtml_lookbook_media_uploader"));
        $this->getUploader()->getConfig()->setUrl(Mage::getModel("adminhtml/url")->addSessionParam()->getUrl("*/*/image"))->setFileField("image")->setFilters(array("images" => array("label" => Mage::helper("adminhtml")->__("Images (.gif, .jpg, .png)"), "files" => array("*.gif", "*.jpg", "*.jpeg", "*.png"))));
        $this->setChild("delete_button", $this->getLayout()->createBlock("adminhtml/widget_button")->addData(array("id" => "{{id}}-delete", "class" => "delete", "type" => "button", "label" => Mage::helper("adminhtml")->__("Remove"), "onclick" => $this->getJsObjectName() . ".removeFile('{{fileId}}')")));
        return parent::_prepareLayout();
    }

    public function getImagesJson() {
       //  $id = $this->getRequest()->getParam('id');
      //  $_model = Mage::getModel('lookbook/lookbook')->load($id);
      //  var_dump($_model->getId());
//        Mage::getSingleton('adminhtml/session')->getLookBookData()
      $_model = Mage::registry("lookbook_data");
      // var_dump($_model->getData('image'));
        
        $_data = $_model->getData('image'); //
        
        
        if (is_array($_data) and sizeof($_data) > 0) {
            $_result = array();
            foreach ($_data as &$_item) {
                $_result[] = array("value_id" => $_item["image_id"], 
                    "url" => Mage::getSingleton("lookbook/config")->getBaseMediaUrl() . $_item["file"], 
                    "file" => $_item["file"], 
                    "label" => $_item["label"], 
                    "position" => $_item["position"], 
                    "is_main" => $_item["is_main"],
                    "disabled" => $_item["disabled"]);
            }return Zend_Json::encode($_result);
        }return "[]";
    }

    public function getUploader() {
        return $this->getChild("uploader");
    }

    public function getUploaderHtml() {
        return $this->getChildHtml("uploader");
    }

    public function getJsObjectName() {
        return $this->getHtmlId() . "JsObject";
    }

    public function getAddImagesButton() {
        return $this->getButtonHtml(Mage::helper("catalog")->__("Add New Images"), $this->getJsObjectName() . ".showUploader()", "add", $this->getHtmlId() . "_add_images_button");
    }

    public function getImagesValuesJson() {
        $values = array();
        return Zend_Json::encode($values);
    }

    public function getMediaAttributes() {
        
    }

    public function getImageTypes() {
        $type = array();
        $type["gallery"]["label"] = "igallery";
        $type["gallery"]["field"] = "igallery";
        $imageTypes = array();
        return $type;
    }

    public function getImageTypesJson() {
        return Zend_Json::encode($this->getImageTypes());
    }

    public function getCustomRemove() {
        return $this->setChild("delete_button", $this->getLayout()->createBlock("adminhtml/widget_button")->addData(array("id" => "{{id}}-delete", "class" => "delete", "type" => "button", "label" => Mage::helper("adminhtml")->__("Remove"), "onclick" => $this->getJsObjectName() . ".removeFile('{{fileId}}')")));
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml("delete_button");
    }

    public function getCustomValueId() {
        return $this->setChild("value_id", $this->getLayout()->createBlock("adminhtml/widget_button")->addData(array("id" => "{{id}}-value", "class" => "value_id", "type" => "text", "label" => Mage::helper("adminhtml")->__("ValueId"),)));
    }

    public function getValueIdHtml() {
        return $this->getChildHtml("value_id");
    }

}

?>

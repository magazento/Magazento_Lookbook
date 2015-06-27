<?php

class Magazento_LookBook_Block_Adminhtml_Lookbook_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'lookbook';
        $this->_controller = 'adminhtml_lookbook';
        
        $this->_updateButton('save', 'label', Mage::helper('lookbook')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('lookbook')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('lookbook_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'lookbook_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'lookbook_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('lookbook_data') && Mage::registry('lookbook_data')->getId() ) {
            return Mage::helper('lookbook')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('lookbook_data')->getTitle()));
        } else {
            return Mage::helper('lookbook')->__('Add Item');
        }
    }
}
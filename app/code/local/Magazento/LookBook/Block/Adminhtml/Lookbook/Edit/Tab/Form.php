<?php

class Magazento_LookBook_Block_Adminhtml_Lookbook_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('lookbook_form', array('legend' => Mage::helper('lookbook')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('lookbook')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'label' => Mage::helper('lookbook')->__('Visible In'),
                'required' => false,
                'name' => 'stores[]',
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                    //   'value'     => $_model->getStoreId()
            ));
        } else {
            $fieldset->addField('stores', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

//        $fieldset->addField('image', 'file', array(
//            'label' => Mage::helper('lookbook')->__('Image'),
//            'required' => false,
//            'name' => 'image',
//        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('lookbook')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('lookbook')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('lookbook')->__('Disabled'),
                ),
            ),
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('from_date', 'date', array(
            'name' => 'from_date',
            'label' => Mage::helper('lookbook')->__('From Date'),
            'title' => Mage::helper('lookbook')->__('From Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormatIso
        ));
        $fieldset->addField('to_date', 'date', array(
            'name' => 'to_date',
            'label' => Mage::helper('lookbook')->__('To Date'),
            'title' => Mage::helper('lookbook')->__('To Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormatIso
        ));
//        $configSettings = Mage::getSingleton('cms/wysiwyg_config')->getConfig( 
//array( 
//'add_widgets'=> false, 
//'add_variables'=> false, 
//'add_images'=> false, 
//'files_browser_window_url'> $this->getBaseUrl().'admin/cms_wysiwyg_images/index/', 
//)); 

        $configSettings = Mage::getSingleton('lookbook/wysiwyg_config')->getConfig();
        $fieldset->addField('descr', 'editor', array(
            'name' => 'descr',
            'label' => Mage::helper('lookbook')->__('Content'),
            'title' => Mage::helper('lookbook')->__('Content'),
            'style' => 'width:700px; height:500px;',
            'wysiwyg' => true,
            'required' => true,
            'config' => $configSettings,
        ));

        if (Mage::getSingleton('adminhtml/session')->getLookBookData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getLookBookData());
            Mage::getSingleton('adminhtml/session')->setLookBookData(null);
        } elseif (Mage::registry('lookbook_data')) {
            $form->setValues(Mage::registry('lookbook_data')->getData());
        }
        return parent::_prepareForm();
    }

}
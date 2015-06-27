<?php
/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Lookbook_Model_Wysiwyg_Config extends Mage_Cms_Model_Wysiwyg_Config {

    public function getConfig($data = array()) {


        $config = parent::getConfig($data);
        $config->setData('files_browser_window_url', Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index/'));
        $config->setData('directives_url', Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'));
        $config->setData('directives_url_quoted', preg_quote($config->getData('directives_url')));
        $config->setData('widget_window_url', Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'));


        return $config;
    }

}

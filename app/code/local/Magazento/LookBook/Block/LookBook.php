<?php
class Magazento_LookBook_Block_LookBook extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getLookBook()     
     { 
        if (!$this->hasData('lookbook')) {
            $this->setData('lookbook', Mage::registry('lookbook'));
        }
        return $this->getData('lookbook');
        
    }
}
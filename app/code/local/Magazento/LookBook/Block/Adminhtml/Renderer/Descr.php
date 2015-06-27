<?php
class Magazento_LookBook_Block_Adminhtml_Renderer_Descr extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action{
     
    public function render(Varien_Object $row) {
      //  var_dump($row);
        $val = $row->getDescr();
       
       $out=strip_tags($val);

        return $out;
     }
}

?>

<?php
class Magazento_LookBook_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action{
     
    public function render(Varien_Object $row) {
      //  var_dump($row);
        $val = $row->getFile();
       if ($val){
        $url = Mage::getBaseUrl('media'). 'lookbook'. DS . $val;

        $out ='<center><a href="' . $url . '" target="_blank" id="imageurl">';
        $out .= "<img src=" . $url . " width='80px' ";
        $out .=" />";
        $out .= '</a></center>';
       }
       else 
           $out=Mage::Helper('lookbook')->__('No main image');
        return $out;
     }
}

?>

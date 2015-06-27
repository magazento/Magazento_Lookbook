<?php

class Magazento_LookBook_Block_Adminhtml_Lookbook_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('lookbookGrid');
        $this->setDefaultSort('lookbook_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('lookbook/lookbook')->getCollection()
                ->addMainImagesData();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('lookbook_id', array(
            'header' => Mage::helper('lookbook')->__('ID'),
            'align' => 'right',
            'width' => '30px',
            'index' => 'lookbook_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('lookbook')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'width' => '150px',
        ));

        /*
          $this->addColumn('content', array(
          'header'    => Mage::helper('lookbook')->__('Item Content'),
          'width'     => '150px',
          'index'     => 'content',
          ));
         */
        $this->addColumn('simage', array(
            'header' => Mage::helper('catalog')->__('Image-Thumb'),
            'type' => 'image',
            'width' => '80px',
            'renderer' => new Magazento_LookBook_Block_Adminhtml_Renderer_Image()
        ));
        $this->addColumn('descr', array(
            'header' => Mage::helper('lookbook')->__('Item Content'),
            //'width'     => '550px',
            'renderer' => new Magazento_LookBook_Block_Adminhtml_Renderer_Descr()
        ));
         $this->addColumn('from_date', array(
            'header' => Mage::helper('lookbook')->__('From'),
            'index' => 'from_date',
            'type' => 'date',
            'width' => '100px',
        ));
          $this->addColumn('to_date', array(
            'header' => Mage::helper('lookbook')->__('To'),
            'index' => 'to_date',
            'type' => 'date',
            'width' => '100px',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('lookbook')->__('Status'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('lookbook')->__('Action'),
            'width' => '80px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('lookbook')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('lookbook')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('lookbook')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('lookbook_id');
        $this->getMassactionBlock()->setFormFieldName('lookbook');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('lookbook')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('lookbook')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('lookbook/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('lookbook')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('lookbook')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
<?php

class TM_EasyBanner_Block_Adminhtml_Placeholder_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @param array $arguments Object data
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('chooser_name');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('*/easybanner_placeholder_widget/chooser', array('uniq_id' => $uniqId));

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);


        if ($element->getValue()) {
            $placeholder = Mage::getModel('easybanner/placeholder')->load($element->getValue(), 'name');
            if ($placeholder->getId()) {
                $chooser->setLabel($placeholder->getName());
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var name = trElement.down("td").next().innerHTML.replace(/^\s+|\s+$/g,"");
                '.$chooserJsObject.'.setElementValue(name);
                '.$chooserJsObject.'.setElementLabel(name);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare pages collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('easybanner/placeholder')->getCollection();
        // $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for pages grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('chooser_id', array(
            'header'    => Mage::helper('easybanner')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'placeholder_id',
            'type'      => 'number'
        ));

        $this->addColumn('chooser_name', array(
            'header'    => Mage::helper('easybanner')->__('Name'),
            'align'     => 'left',
            'index'     => 'name'
        ));

        $this->addColumn('chooser_parent_block', array(
            'header'    => Mage::helper('easybanner')->__('Parent Block'),
            'align'     => 'left',
            'width'     => '200px',
            'index'     => 'parent_block'
        ));

        $this->addColumn('chooser_status', array(
            'header'    => Mage::helper('easybanner')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                0 => 'Disabled'
            )
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/easybanner_placeholder_widget/chooser', array('_current' => true));
    }
}

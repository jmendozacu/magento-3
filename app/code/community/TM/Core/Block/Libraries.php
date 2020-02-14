<?php

class TM_Core_Block_Libraries extends Mage_Core_Block_Abstract
{
    /**
     * Add jQuery library
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $head = $this->getLayout()->getBlock('head');
        if ($head && Mage::getStoreConfigFlag('tmcore/jslibs/jquery')) {
            $jqueryFiles = array(
                'lib/jquery/jquery-3.4.1.min.js', // Magento 1.9.3.3
            );
            $baseDir = Mage::getBaseDir();
            foreach ($jqueryFiles as $fileName) {
                if (file_exists($baseDir . '/js/' . $fileName)) {
                    $head->addJs($fileName);
                    break;
                }
            }
            $head->addJs('lib/jquery/noconflict.js');
        }
        return parent::_prepareLayout();
    }
}

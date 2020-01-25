<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Sphinx Search Ultimate
 * @version   2.3.2
 * @build     1070
 * @copyright Copyright (C) 2015 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_SearchAutocomplete_Block_Layout extends Mage_Core_Block_Template
{
    private $css;
    private $template;

    public function __construct()
    {
        $this->defineTheme();
    }

    /**
     * Set css and template file for search box
     */
    private function defineTheme()
    {
        $theme = Mage::getSingleton('searchautocomplete/config')->getTheme();
        switch ($theme) {
            case 'default':
                $css      = Mirasvit_SearchAutocomplete_Model_Config::CSS_DEFAULT;
                $template = Mirasvit_SearchAutocomplete_Model_Config::TPL_DEFAULT;
                break;
            case 'rwd':
                $css      = Mirasvit_SearchAutocomplete_Model_Config::CSS_RWD;
                $template = Mirasvit_SearchAutocomplete_Model_Config::TPL_DEFAULT;
                break;
            default:
                $css      = Mirasvit_SearchAutocomplete_Model_Config::CSS_AMAZON;
                $template = Mirasvit_SearchAutocomplete_Model_Config::TPL_AMAZON;
                break;
        }
        $this->css = $css;
        $this->template = $template;
    }

    public function addSearchAutocomplete()
    {
        $this->addSearchCss();
        $this->addForm();
    }

    /**
     * Add css file to layout
     */
    public function addSearchCss()
    {
        $head = $this->getLayout()->getBlock('head');
        $head->addCss($this->css);
    }

    /**
     * Add searchautocomplete block to layout
     */
    public function addForm()
    {
        $searchBlock = $this->getLayout()->getBlock('top.search');
        $searchReference = $searchBlock->getParentBlock();
        $searchReference->unsetChild($searchBlock->getBlockAlias());
        $searchBlock = $this->getLayout()->createBlock('searchautocomplete/form')
            ->setTemplate($this->template)
            ->setNameInLayout('top.search');
        $searchReference->setChild('topSearch', $searchBlock);
    }
} 
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


class Mirasvit_SearchIndex_Helper_Route extends Mage_Core_Helper_Abstract
{
    public function process(Mage_Core_Controller_Front_Action $controller)
    {
        $searchText = $this->getSearchQuery($controller->getRequest());
        $url = Mage::getUrl('catalogsearch/result', array('_query' => array('q' => $searchText)));
        $controller->getResponse()
            ->clearHeaders()
            ->setRedirect($url)
            ->sendResponse();
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @return string $query
     */
    private function getSearchQuery(Mage_Core_Controller_Request_Http $request)
    {
        $query = preg_replace('/(\W|html|php)+/', ' ', $request->getRequestString());
        if (count($request->getQuery()) > 0) {
            $query .= implode(' ', $request->getQuery());
        }

        return $query;
    }
} 
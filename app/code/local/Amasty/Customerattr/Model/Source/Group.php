<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Customerattr
 */
class Amasty_Customerattr_Model_Source_Group
{
    public function toOptionArray()
    {
        $groups = Mage::getResourceModel('customer/group_collection')->load()
            ->toOptionArray();
        unset($groups[0]);
        return $groups;
    }
}

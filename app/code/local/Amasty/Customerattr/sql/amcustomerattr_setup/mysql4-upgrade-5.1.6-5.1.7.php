<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Customerattr
 */
$installer = $this;

$installer->startSetup();

$installer->run(
    "
    ALTER TABLE `{$this->getTable('customer/eav_attribute')}` ADD `required_on_front` TINYINT( 1 ) UNSIGNED NOT NULL ;
"
);

$installer->endSetup(); 
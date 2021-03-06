<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('eav/attribute');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('parent_dropdown', $cols)) {
    $installer->run("ALTER TABLE `{$this->getTable('eav/attribute')}` ADD `parent_dropdown` INT( 10 )  UNSIGNED NOT NULL");
}

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('eav/attribute_option');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('parent_option_id', $cols)) {
    $installer->run("ALTER TABLE `{$this->getTable('eav/attribute_option')}` ADD `parent_option_id` INT( 10 )  UNSIGNED NOT NULL");
}

$installer->endSetup();
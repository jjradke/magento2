<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Tools
 * @package    system_configuration
 * @copyright  Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once __DIR__ . '/../../../../../app/bootstrap.php';
$rootDir = realpath(__DIR__ . '/../../../../..');
try {
    $entryPoint = new \Magento\App\EntryPoint\EntryPoint($rootDir, array());

    $objectManager = new \Magento\App\ObjectManager();
    /** @var $configModel \Magento\App\ReinitableConfigInterface */
    $configModel = $objectManager->get('Magento\App\ReinitableConfigInterface');
    $configModel->reinit();
    $config = array();

    foreach (glob(__DIR__ . '/AliasesMap/cms_content_tables_*.php', GLOB_BRACE) as $configFile) {
        $config = array_merge($config, include $configFile);
    }

    foreach ($config as $table => $field) {
        updateFieldForTable($objectManager, $table, $field);
    }
} catch (\Exception $e) {
    echo "Make sure that you launch this script with Magento 2 configured sources. \n\n";
    echo $e->getMessage();
}

/**
 * Replace {{skin url=""}} with {{view url=""}} for given table field
 *
 * @param \Magento\ObjectManager $objectManager
 * @param string $table
 * @param string $col
 * @return void
 */
function updateFieldForTable($objectManager, $table, $col)
{
    /** @var $installer \Magento\Core\Model\Resource\Setup */
    $installer = $objectManager->create('Magento\Core\Model\Resource\Setup', array('resourceName' => 'core_setup'));
    $installer->startSetup();

    $table = $installer->getTable($table);
    echo '-----' . "\n";
    if ($installer->getConnection()->isTableExists($table)) {
        echo 'Table `' . $table . "` processed\n";

        $indexList = $installer->getConnection()->getIndexList($table);
        $pkField = array_shift($indexList[$installer->getConnection()->getPrimaryKeyName($table)]['fields']);
        /** @var $select \Magento\Db\Select */
        $select = $installer->getConnection()->select()->from($table, array('id' => $pkField, 'content' => $col));
        $result = $installer->getConnection()->fetchPairs($select);

        echo 'Records count: ' . count($result) . ' in table: `' . $table . "`\n";

        $logMessages = array();
        foreach ($result as $recordId => $string) {
            $content = str_replace('{{skin', '{{view', $string, $count);
            if ($count) {
                $installer->getConnection()->update(
                    $table,
                    array($col => $content),
                    $installer->getConnection()->quoteInto($pkField . '=?', $recordId)
                );
                $logMessages['replaced'][] = 'Replaced -- Id: ' . $recordId . ' in table `' . $table . '`';
            } else {
                $logMessages['skipped'][] = 'Skipped -- Id: ' . $recordId . ' in table `' . $table . '`';
            }
        }
        if (count($result)) {
            printLog($logMessages);
        }
    } else {
        echo 'Table `' . $table . "` was not found\n";
    }
    $installer->endSetup();
    echo '-----' . "\n";
}

/**
 * Print array of messages
 *
 * @param array $logMessages
 * @return void
 */
function printLog($logMessages)
{
    foreach ($logMessages as $stringsArray) {
        echo "\n";
        echo implode("\n", $stringsArray);
        echo "\n";
    }
}

<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package
 * @since           2.5.9
 * @author          Michael Beck (aka Mamba)
 */

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Common;
use XoopsModules\Modulebuilder\Utility;

require_once \dirname(\dirname(\dirname(__DIR__))) . '/include/cp_header.php';
require \dirname(__DIR__) . '/preloads/autoloader.php';

$op = \Xmf\Request::getCmd('op', '');

$moduleDirName = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$helper = Modulebuilder\Helper::getInstance();
// Load language files
$helper->loadLanguage('common');

switch ($op) {
    case 'load':
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('../admin/index.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            loadSampleData();
        } else {
            xoops_cp_header();
            xoops_confirm(['ok' => 1, 'op' => 'load'], 'index.php', \sprintf(\constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA_OK')), \constant('CO_' . $moduleDirNameUpper . '_' . 'CONFIRM'), true);
            xoops_cp_footer();
        }
        break;
    case 'save':
        saveSampleData();
        break;
}

// XMF TableLoad for SAMPLE data

function loadSampleData()
{
    global $xoopsConfig;

    $moduleDirName = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

    $utility      = new Modulebuilder\Utility();
    $configurator = new Common\Configurator();

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    $language = 'english/';
    if (\is_dir(__DIR__ . '/' . $xoopsConfig['language'])) {
        $language = $xoopsConfig['language'] . '/';
    }

    foreach ($tables as $table) {
        $tabledata = \Xmf\Yaml::readWrapped($language . $table . '.yml');
        \Xmf\Database\TableLoad::truncateTable($table);
        \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
    }

    //  ---  COPY test folder files ---------------
    if (\is_array($configurator->copyTestFolders) && \count($configurator->copyTestFolders) > 0) {
        //        $file = __DIR__ . '/../testdata/images/';
        foreach (\array_keys($configurator->copyTestFolders) as $i) {
            $src  = $configurator->copyTestFolders[$i][0];
            $dest = $configurator->copyTestFolders[$i][1];
            $utility::rcopy($src, $dest);
        }
    }
    \redirect_header('../admin/index.php', 1, \constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}

function saveSampleData()
{
    global $xoopsConfig;

    $configurator = new Common\Configurator();

    $moduleDirName = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    $languageFolder = __DIR__ . '/' . $xoopsConfig['language'];
    if (!\file_exists($languageFolder . '/')) {
        Utility::createFolder($languageFolder . '/');
    }
    $exportFolder = $languageFolder . '/Exports-' . date('Y-m-d-H-i-s') . '/';
    Utility::createFolder($exportFolder);

    foreach ($tables as $table) {
        \Xmf\Database\TableLoad::saveTableToYamlFile($table, $exportFolder . $table . '.yml');
    }

    //  ---  COPY test folder files ---------------
    if (\is_array($configurator->copyTestFolders) && \count($configurator->copyTestFolders) > 0) {
        foreach (\array_keys($configurator->copyTestFolders) as $i) {
            $src  = $configurator->copyTestFolders[$i][1];
            $dest = $configurator->copyTestFolders[$i][0];
            Utility::rcopy($src, $dest);
        }
    }
    \redirect_header('../admin/index.php', 1, \constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA_SUCCESS'));
}

function exportSchema()
{
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

    try {
        // TODO set exportSchema
        //        $migrate = new Modulebuilder\Migrate($moduleDirName);
        //        $migrate->saveCurrentSchema();
        //
        //        \redirect_header('../admin/index.php', 1, \constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS'));
    }
    catch (\Exception $e) {
        exit(\constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR'));
    }

}
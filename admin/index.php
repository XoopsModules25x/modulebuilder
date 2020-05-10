<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

use XoopsModules\Modulebuilder\Common;

$GLOBALS['xoopsOption']['template_main'] = 'modulebuilder_index.tpl';

include __DIR__ . '/header.php';
$countSettings = $helper->getHandler('Settings')->getCount();
$countModules  = $helper->getHandler('Modules')->getCount();
$countTables   = $helper->getHandler('Tables')->getCount();
$countFields   = $helper->getHandler('Fields')->getCount();
$countFiles    = $helper->getHandler('Morefiles')->getCount();
unset($criteria);

//$templateMain = 'modulebuilder_index.tpl';
$adminObject->addInfoBox(_AM_MODULEBUILDER_ADMIN_NUMMODULES);
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MODULEBUILDER_THEREARE_NUMSETTINGS . '</label>', $countSettings), 'Blue');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MODULEBUILDER_THEREARE_NUMMODULES . '</label>', $countModules), 'Green');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MODULEBUILDER_THEREARE_NUMTABLES . '</label>', $countTables), 'Orange');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MODULEBUILDER_THEREARE_NUMFIELDS . '</label>', $countFields), 'Gray');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MODULEBUILDER_THEREARE_NUMFILES . '</label>', $countFiles), 'Red');
// Upload Folders
$folder = [
    TDMC_UPLOAD_PATH,
    TDMC_UPLOAD_REPOSITORY_PATH,
    TDMC_UPLOAD_IMGMOD_PATH,
    TDMC_UPLOAD_IMGTAB_PATH,
];

// Uploads Folders Created
//foreach (array_keys($folder) as $i) {
//    $utility::createFolder($folder[$i]);
//    $adminObject->addConfigBoxLine($folder[$i], 'folder');
//    $adminObject->addConfigBoxLine([$folder[$i], '777'], 'chmod');
//}

//------ check Upload Folders ---------------

$adminObject->addConfigBoxLine('');
$redirectFile = $_SERVER['SCRIPT_NAME'];

foreach (array_keys($folder) as $i) {
    $adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($folder[$i], 0777, $redirectFile));
}

//foreach ($pathArray as $path) {
//    $adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path['dir'], $path['perm'], $languageConstants, $path['action']));
//}


$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    $yamlFile            = dirname(__DIR__) . '/config/admin.yml';
    $config              = loadAdminConfig($yamlFile);
    $displaySampleButton = $config['displaySampleButton'];

    if (1 == $displaySampleButton) {
        xoops_loadLanguage('admin/modulesadmin', 'system');
        require __DIR__ . '/../testdata/index.php';

        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
        //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
    } else {
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
        $displaySampleButton = $config['displaySampleButton'];
    }
    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = \Xmf\Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app['displaySampleButton'] = 0;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app['displaySampleButton'] = 1;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = \Xmf\Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}

echo $utility::getServerStats();





//$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
//$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());

include __DIR__ . '/footer.php';

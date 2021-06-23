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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */
require \dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

/** @var \XoopsModules\Modulebuilder\Helper $helper */
$helper = \XoopsModules\Modulebuilder\Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (\is_object($helper->getModule())) {
    //    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

$moduleHandler = \xoops_getHandler('module');
$xoopsModule   = \XoopsModule::getByDirname($moduleDirName);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');
$modPathIcon32 = $moduleInfo->getInfo('modicons32');

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/dashboard.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU2,
    'link'  => 'admin/settings.php',
    'icon'  => $modPathIcon32 . '/settings.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU3,
    'link'  => 'admin/modules.php',
    'icon'  => $modPathIcon32 . '/addmodule.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU4,
    'link'  => 'admin/tables.php',
    'icon'  => $modPathIcon32 . '/addtable.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU5,
    'link'  => 'admin/fields.php',
    'icon'  => $modPathIcon32 . '/fields.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU6,
    'link'  => 'admin/morefiles.php',
    'icon'  => $modPathIcon32 . '/files.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU7,
    'link'  => 'admin/building.php',
    'icon'  => $modPathIcon32 . '/builder.png',
];

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ADMENU8,
    'link'  => 'admin/devtools.php',
    'icon'  => $modPathIcon32 . '/devtools.png',
];

//Feedback
$adminmenu[] = [
    'title' => \constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_FEEDBACK'),
    'link'  => 'admin/feedback.php',
    'icon'  => $pathIcon32 . 'mail_foward.png',
];

if (\is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => \constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link'  => 'admin/migrate.php',
        'icon'  => $pathIcon32 . 'database_go.png',
    ];
}

$adminmenu[] = [
    'title' => \_MI_MODULEBUILDER_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . 'about.png',
];

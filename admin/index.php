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

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Modulebuilder\{
    Common,
    Common\TestdataButtons,
    Forms,
    Helper,
    Utility
};

$GLOBALS['xoopsOption']['template_main'] = 'modulebuilder_index.tpl';

require __DIR__ . '/header.php';
$countSettings = $helper->getHandler('Settings')->getCount();
$countModules  = $helper->getHandler('Modules')->getCount();
$countTables   = $helper->getHandler('Tables')->getCount();
$countFields   = $helper->getHandler('Fields')->getCount();
$countFiles    = $helper->getHandler('Morefiles')->getCount();
unset($criteria);

//$templateMain = 'modulebuilder_index.tpl';
$adminObject->addInfoBox(\_AM_MODULEBUILDER_ADMIN_NUMMODULES);
$adminObject->addInfoBoxLine(\sprintf('<label>' . \_AM_MODULEBUILDER_THEREARE_NUMSETTINGS . '</label>', $countSettings), 'Blue');
$adminObject->addInfoBoxLine(\sprintf('<label>' . \_AM_MODULEBUILDER_THEREARE_NUMMODULES . '</label>', $countModules), 'Green');
$adminObject->addInfoBoxLine(\sprintf('<label>' . \_AM_MODULEBUILDER_THEREARE_NUMTABLES . '</label>', $countTables), 'Orange');
$adminObject->addInfoBoxLine(\sprintf('<label>' . \_AM_MODULEBUILDER_THEREARE_NUMFIELDS . '</label>', $countFields), 'Gray');
$adminObject->addInfoBoxLine(\sprintf('<label>' . \_AM_MODULEBUILDER_THEREARE_NUMFILES . '</label>', $countFiles), 'Red');
// Upload Folders
$folder = [
    TDMC_UPLOAD_PATH,
    TDMC_UPLOAD_REPOSITORY_PATH,
    TDMC_UPLOAD_IMGMOD_PATH,
    TDMC_UPLOAD_IMGTAB_PATH,
];

//------ check Upload Folders ---------------

$adminObject->addConfigBoxLine();
$redirectFile = $_SERVER['SCRIPT_NAME'];

foreach (\array_keys($folder) as $i) {
    $adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($folder[$i], 0777, $redirectFile));
}

$adminObject->displayNavigation(\basename(__FILE__));

//------------- Test Data Buttons ----------------------------
if ($helper->getConfig('displaySampleButton')) {
    TestdataButtons::loadButtonConfig($adminObject);
    $adminObject->displayButton('left', '');
}
$op = Request::getString('op', 0, 'GET');
switch ($op) {
    case 'hide_buttons':
        TestdataButtons::hideButtons();
        break;
    case 'show_buttons':
        TestdataButtons::showButtons();
        break;
}
//------------- End Test Data Buttons ----------------------------

$adminObject->displayIndex();
echo $utility::getServerStats();

//codeDump(__FILE__);
require __DIR__ . '/footer.php';

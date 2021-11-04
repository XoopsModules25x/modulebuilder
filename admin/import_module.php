<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org), TDM Team
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team, TDM Team
 * @author       Mamba
 */

require __DIR__ . '/header.php';
//xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation('modules.php');
$adminObject->addItemButton(_MI_MODULEBUILDER_ADMIN_MODULES, 'modules.php', 'list');
$adminObject->displayButton('left');

require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$formImportModule = new \XoopsThemeForm('modules_import', 'Class', 'modules.php?op=modules_import', 'post', true);
$select_module    = new \XoopsFormSelect(_AM_MODULEBUILDER_MODULE_ID, 'moduleName', '');
$newModuleName    = new \XoopsFormText(_AM_MODULEBUILDER_MODULE_NEW_NAME, 'moduleNewName', 50, 50, '');
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler  = xoops_getHandler('module');
$installed_mods = $moduleHandler->getObjects();
$listed_mods    = [];
$count          = 0;
foreach ($installed_mods as $module) {
    $select_module->addOption($module->getVar('dirname', 'E'), $module->getVar('dirname', 'E'));
}
$select_table = new \XoopsFormSelect(_AM_MODULEBUILDER_TABLE_ID, 'table_id', '');

$sql    = 'SHOW TABLES';
$result = $GLOBALS['xoopsDB']->queryF($sql);

if (!$result) {
    echo '_AM_MODULEBUILDER_ERROR_DATABASE';
    echo '_AM_MODULEBUILDER_ERROR_SQL' . $GLOBALS['xoopsDB']->error();
    exit;
}

while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($result))) {
    $select_table->addOption($row[0], $row[0]);
}
//TODO: add an option to set a name of the new module created by importing the old one
$formImportModule->addElement($select_module);
$formImportModule->addElement($newModuleName);
$op_hidden = new \XoopsFormHidden('op', 'modules_import');
$formImportModule->addElement($op_hidden);
$dbname_hidden = new \XoopsFormHidden('dbname', '');
$formImportModule->addElement($dbname_hidden);

//Submit buttons
$buttonTray    = new \XoopsFormElementTray('', '');
$submit_button = new \XoopsFormButton('', '', _AM_MODULEBUILDER_GENERATE, 'submit');
$buttonTray->addElement($submit_button);

$cancel_button = new \XoopsFormButton('', '', _CANCEL, 'button');
$cancel_button->setExtra('onclick="history.go(-1)"');
$buttonTray->addElement($cancel_button);

$formImportModule->addElement($buttonTray);

$formImportModule->display();

require __DIR__ . '/footer.php';

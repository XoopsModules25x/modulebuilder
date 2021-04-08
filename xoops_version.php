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
 * @since           2.5.7
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 */
\defined('XOOPS_ROOT_PATH') || die('Restricted access');

$moduleDirName      = \basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$modversion['version']             = 3.05;
$modversion['module_status']       = 'RC1';
$modversion['release_date']        = '2020/11/11';
$modversion['name']                = _MI_MODULEBUILDER_NAME;
$modversion['description']         = _MI_MODULEBUILDER_DESC;
$modversion['author']              = 'Xoops TDM';
$modversion['author_website_url']  = 'https://xoops.org/';
$modversion['author_website_name'] = 'Xoops Team Developers Module';
$modversion['author_mail']         = 'goffy@myxoops.org';
$modversion['credits']             = 'Mamba(Xoops), Timgno(Txmod Xoops), Goffy(German Xoops)';
$modversion['help']                = 'page=help';
$modversion['license']             = 'GNU GPL 2.0 or later';
$modversion['license_url']         = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['release_info']        = 'README';
$modversion['release_file']        = 'https://github.com/txmodxoops/modulebuilder-1.91/releases';
$modversion['manual']              = 'MANUAL';
$modversion['manual_file']         = XOOPS_URL . "/modules/{$moduleDirName}/docs/manual.txt";
$modversion['image']               = "assets/images/logoModule.png";
$modversion['dirname']             = $moduleDirName;
// Frameworks icons
$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['sysicons16']     = '../../Frameworks/moduleclasses/icons/16';
$modversion['sysicons32']     = '../../Frameworks/moduleclasses/icons/32';
// Module icons
$modversion['modicons16']          = 'assets/images/icons/16';
$modversion['modicons32']          = 'assets/images/icons/32';
$modversion['targetdir']           = XOOPS_UPLOAD_PATH . "/{$moduleDirName}/repository/";
$modversion['module_website_url']  = 'https://github.com/XoopsModules25x/modulebuilder';
$modversion['module_website_name'] = 'GitHub Xoops';
$modversion['min_php']             = '7.2';
$modversion['min_xoops']           = '2.5.11-Beta 2';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];
//about
$modversion['demo_site_url']       = 'https://xoops.org/';
$modversion['demo_site_name']      = 'Xoops TDM';
$modversion['forum_site_url']      = 'https://xoops.org/modules/newbb/viewtopic.php?post_id=365223';
$modversion['forum_site_name']     = 'ModuleBuilder 3.03 for Testing';
$modversion['module_website_name'] = 'Xoops TDM';
// Admin things
$modversion['system_menu'] = 1;
$modversion['hasAdmin']    = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';
// Templates admin
$modversion['templates'][] = ['file' => 'modulebuilder_about.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_building.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_fields.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_fields_item.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_footer.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_header.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_index.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_modules.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_tables.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_tables_item.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_settings.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_morefiles.tpl', 'description' => '', 'type' => 'admin'];
$modversion['templates'][] = ['file' => 'modulebuilder_devtools.tpl', 'description' => '', 'type' => 'admin'];

// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables created by sql file (without prefix!)
$modversion['tables'] = [
    $moduleDirName . '_' . 'settings',
    $moduleDirName . '_' . 'modules',
    $moduleDirName . '_' . 'tables',
    $moduleDirName . '_' . 'fields',
    $moduleDirName . '_' . 'languages',
    $moduleDirName . '_' . 'fieldtype',
    $moduleDirName . '_' . 'fieldattributes',
    $moduleDirName . '_' . 'fieldnull',
    $moduleDirName . '_' . 'fieldkey',
    $moduleDirName . '_' . 'fieldelements',
    $moduleDirName . '_' . 'morefiles',
];
// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install.php';
$modversion['onUpdate']  = 'include/update.php';
// Menu
$modversion['hasMain'] = 0;
// Config
$c = 1;

$modversion['config'][] = [
    'name'        => 'break' . $c,
    'title'       => '_MI_MODULEBUILDER_CONFIG_BREAK_GENERAL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

\xoops_load('xoopseditorhandler');
$editorHandler = \XoopsEditorHandler::getInstance();

$modversion['config'][] = [
    'name'        => 'modulebuilder_editor',
    'title'       => '_MI_MODULEBUILDER_CONFIG_EDITOR',
    'description' => '_MI_MODULEBUILDER_CONFIG_EDITOR_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtml',
    'options'     => array_flip($editorHandler->getList()),
];

$modversion['config'][] = [
    //Uploads : mimetypes
    'name'        => 'mimetypes_image',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MIMETYPES_IMAGE',
    'description' => '_MI_MODULEBUILDER_CONFIG_MIMETYPES_IMAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'gif|jpeg|pjpeg|png',
];

$modversion['config'][] = [
    //Uploads : maxsize
    'name'        => 'maxsize_image',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MAXSIZE_IMAGE',
    'description' => '_MI_MODULEBUILDER_CONFIG_MAXSIZE_IMAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '5000000',
];

$modversion['config'][] = [
    'name'        => 'settings_adminpager',
    'title'       => '_MI_MODULEBUILDER_CONFIG_SETTINGS_ADMINPAGER',
    'description' => '_MI_MODULEBUILDER_CONFIG_SETTINGS_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'modules_adminpager',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MODULES_ADMINPAGER',
    'description' => '_MI_MODULEBUILDER_CONFIG_MODULES_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'tables_adminpager',
    'title'       => '_MI_MODULEBUILDER_CONFIG_TABLES_ADMINPAGER',
    'description' => '_MI_MODULEBUILDER_CONFIG_TABLES_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'fields_adminpager',
    'title'       => '_MI_MODULEBUILDER_CONFIG_FIELDS_ADMINPAGER',
    'description' => '_MI_MODULEBUILDER_CONFIG_FIELDS_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    'name'        => 'morefiles_adminpager',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MOREFILES_ADMINPAGER',
    'description' => '_MI_MODULEBUILDER_CONFIG_MOREFILES_ADMINPAGER_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

++$c;
$modversion['config'][] = [
    'name'        => 'break' . $c,
    'title'       => '_MI_MODULEBUILDER_CONFIG_BREAK_REQUIRED',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'name',
    'title'       => '_MI_MODULEBUILDER_CONFIG_NAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'My Module',
];

$modversion['config'][] = [
    'name'        => 'dirname',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DIRNAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'mymoduledirname',
];

$modversion['config'][] = [
    'name'        => 'version',
    'title'       => '_MI_MODULEBUILDER_CONFIG_VERSION',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '1.0',
];

$modversion['config'][] = [
    'name'        => 'since',
    'title'       => '_MI_MODULEBUILDER_CONFIG_SINCE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '1.0',
];

$modversion['config'][] = [
    'name'        => 'min_php',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MIN_PHP',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '5.5',
];

$modversion['config'][] = [
    'name'        => 'min_xoops',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MIN_XOOPS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '2.5.9',
];

$modversion['config'][] = [
    'name'        => 'min_admin',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MIN_ADMIN',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '1.2',
];

$modversion['config'][] = [
    'name'        => 'min_mysql',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MIN_MYSQL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '5.5',
];

$modversion['config'][] = [
    'name'        => 'description',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DESCRIPTION',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'This module is for doing following...',
];

$modversion['config'][] = [
    'name'        => 'author',
    'title'       => '_MI_MODULEBUILDER_CONFIG_AUTHOR',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'XOOPS Development Team',
];

$modversion['config'][] = [
    'name'        => 'image',
    'title'       => '_MI_MODULEBUILDER_CONFIG_IMAGE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'empty.png',
];

$modversion['config'][] = [
    'name'        => 'display_admin',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DISPLAY_ADMIN_SIDE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'display_user',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DISPLAY_USER_SIDE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'active_blocks',
    'title'       => '_MI_MODULEBUILDER_CONFIG_ACTIVE_BLOCKS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

$modversion['config'][] = [
    'name'        => 'active_search',
    'title'       => '_MI_MODULEBUILDER_CONFIG_ACTIVE_SEARCH',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'active_comments',
    'title'       => '_MI_MODULEBUILDER_CONFIG_ACTIVE_COMMENTS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'active_notifications',
    'title'       => '_MI_MODULEBUILDER_CONFIG_ACTIVE_NOTIFICATIONS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'active_permissions',
    'title'       => '_MI_MODULEBUILDER_CONFIG_ACTIVE_PERMISSIONS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['config'][] = [
    'name'        => 'inroot_copy',
    'title'       => '_MI_MODULEBUILDER_CONFIG_INROOT_COPY',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

++$c;
$modversion['config'][] = [
    'name'        => 'break' . $c,
    'title'       => '_MI_MODULEBUILDER_CONFIG_BREAK_OPTIONAL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head',
];

$modversion['config'][] = [
    'name'        => 'author_email',
    'title'       => '_MI_MODULEBUILDER_CONFIG_AUTHOR_EMAIL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'name@site.com',
];

$modversion['config'][] = [
    'name'        => 'author_website_url',
    'title'       => '_MI_MODULEBUILDER_CONFIG_AUTHOR_WEBSITE_URL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'https://xoops.org',
];

$modversion['config'][] = [
    'name'        => 'author_website_name',
    'title'       => '_MI_MODULEBUILDER_CONFIG_AUTHOR_WEBSITE_NAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'XOOPS Project',
];

$modversion['config'][] = [
    'name'        => 'credits',
    'title'       => '_MI_MODULEBUILDER_CONFIG_CREDITS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'XOOPS Development Team',
];

$modversion['config'][] = [
    'name'        => 'license',
    'title'       => '_MI_MODULEBUILDER_CONFIG_LICENSE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'GPL 2.0 or later',
];

$modversion['config'][] = [
    'name'        => 'license_url',
    'title'       => '_MI_MODULEBUILDER_CONFIG_LICENSE_URL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'http://www.gnu.org/licenses/old-licenses/gpl-2.0.html',
];

$modversion['config'][] = [
    'name'        => 'repository',
    'title'       => '_MI_MODULEBUILDER_CONFIG_REPOSITORY',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'https://github.com/txmodxoops/ModuleBuilder-1.91',
];

$modversion['config'][] = [
    'name'        => 'release_info',
    'title'       => '_MI_MODULEBUILDER_CONFIG_RELEASE_INFO',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'release_info',
];

$modversion['config'][] = [
    'name'        => 'release_file',
    'title'       => '_MI_MODULEBUILDER_CONFIG_RELEASE_FILE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'release_info file',
];

$modversion['config'][] = [
    'name'        => 'manual',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MANUAL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'link to manual file',
];

$modversion['config'][] = [
    'name'        => 'manual_file',
    'title'       => '_MI_MODULEBUILDER_CONFIG_MANUAL_FILE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'install.txt',
];

$modversion['config'][] = [
    'name'        => 'demo_site_url',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DEMO_SITE_URL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'https://xoops.org',
];

$modversion['config'][] = [
    'name'        => 'demo_site_name',
    'title'       => '_MI_MODULEBUILDER_CONFIG_DEMO_SITE_NAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'XOOPS Demo Site',
];

$modversion['config'][] = [
    'name'        => 'support_url',
    'title'       => '_MI_MODULEBUILDER_CONFIG_SUPPORT_URL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'https://xoops.org/modules/newbb',
];

$modversion['config'][] = [
    'name'        => 'support_name',
    'title'       => '_MI_MODULEBUILDER_CONFIG_SUPPORT_NAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'Support Forum',
];

$modversion['config'][] = [
    'name'        => 'website_url',
    'title'       => '_MI_MODULEBUILDER_CONFIG_WEBSITE_URL',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'www.xoops.org',
];

$modversion['config'][] = [
    'name'        => 'website_name',
    'title'       => '_MI_MODULEBUILDER_CONFIG_WEBSITE_NAME',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'XOOPS Project',
];

$modversion['config'][] = [
    'name'        => 'release_date',
    'title'       => '_MI_MODULEBUILDER_CONFIG_RELEASE_DATE',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => date(_DBDATESTRING),
];

$modversion['config'][] = [
    'name'        => 'status',
    'title'       => '_MI_MODULEBUILDER_CONFIG_STATUS',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'Beta 1',
];

$modversion['config'][] = [
    'name'        => 'donations',
    'title'       => '_MI_MODULEBUILDER_CONFIG_PAYPAL_BUTTON',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '6KJ7RW5DR3VTJ',
];

$modversion['config'][] = [
    'name'        => 'subversion',
    'title'       => '_MI_MODULEBUILDER_CONFIG_SUBVERSION',
    'description' => '_MI_MODULEBUILDER_CONFIG_',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '13040',
];

unset($c);

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

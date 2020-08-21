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
 * @author          Xoops Team Developement Modules - https://xoops.org
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

\define('_MI_MODULEBUILDER_NAME', 'ModuleBuilder');
\define('_MI_MODULEBUILDER_DESC', 'Creation modules developed by TDM');
//Menu
\define('_MI_MODULEBUILDER_ADMENU1', 'Dashboard');
\define('_MI_MODULEBUILDER_ADMENU2', 'Settings');
\define('_MI_MODULEBUILDER_ADMENU3', 'Modules');
\define('_MI_MODULEBUILDER_ADMENU4', 'Tables');
\define('_MI_MODULEBUILDER_ADMENU5', 'Fields');
\define('_MI_MODULEBUILDER_ADMENU6', 'More Files');
\define('_MI_MODULEBUILDER_ADMENU7', 'Building');
\define('_MI_MODULEBUILDER_ADMENU8', 'Developers Tools');
\define('_MI_MODULEBUILDER_ABOUT', 'About');
// 1.37
\define('_MI_MODULEBUILDER_CONFIG_EDITOR', 'Editor');
\define('_MI_MODULEBUILDER_CONFIG_EDITOR_DESC', 'Select an editor for the description');
//1.381
\define('_MI_MODULEBUILDER_CONFIG_NAME', 'Module Name');
// Added in version 1.91
\define('_MI_MODULEBUILDER_CONFIG_DIRNAME', 'Directory Name');
// ---------------------
\define('_MI_MODULEBUILDER_CONFIG_VERSION', 'Module Version');
\define('_MI_MODULEBUILDER_CONFIG_SINCE', 'Module Since');
\define('_MI_MODULEBUILDER_CONFIG_AUTHOR', 'Module Author');
\define('_MI_MODULEBUILDER_CONFIG_AUTHOR_EMAIL', "Author's Email");
\define('_MI_MODULEBUILDER_CONFIG_AUTHOR_WEBSITE_URL', "Author's Website URL");
\define('_MI_MODULEBUILDER_CONFIG_AUTHOR_WEBSITE_NAME', "Author's Website Name");
\define('_MI_MODULEBUILDER_CONFIG_LICENSE', 'License');
\define('_MI_MODULEBUILDER_CONFIG_LICENSE_URL', 'License URL');
\define('_MI_MODULEBUILDER_CONFIG_REPOSITORY', 'Repository URL');
\define('_MI_MODULEBUILDER_CONFIG_CREDITS', 'Credits');
\define('_MI_MODULEBUILDER_CONFIG_RELEASE_INFO', 'Modules Release Info');
\define('_MI_MODULEBUILDER_CONFIG_RELEASE_FILE', 'Module Release File');
\define('_MI_MODULEBUILDER_CONFIG_MANUAL', 'Modules Manual');
\define('_MI_MODULEBUILDER_CONFIG_MANUAL_FILE', 'Manual file');
\define('_MI_MODULEBUILDER_CONFIG_IMAGE', 'Modules Image');
\define('_MI_MODULEBUILDER_CONFIG_DEMO_SITE_URL', 'Demo Website URL');
\define('_MI_MODULEBUILDER_CONFIG_DEMO_SITE_NAME', 'Demo Website Name');
\define('_MI_MODULEBUILDER_CONFIG_SUPPORT_URL', 'Support Website URL');
\define('_MI_MODULEBUILDER_CONFIG_SUPPORT_NAME', 'Support Website');
\define('_MI_MODULEBUILDER_CONFIG_WEBSITE_URL', 'Module website URL');
\define('_MI_MODULEBUILDER_CONFIG_WEBSITE_NAME', 'Module Website name');
\define('_MI_MODULEBUILDER_CONFIG_RELEASE_DATE', 'Release Date');
\define('_MI_MODULEBUILDER_CONFIG_STATUS', 'Module status');
\define('_MI_MODULEBUILDER_CONFIG_DISPLAY_ADMIN_SIDE', 'Visible in Admin Panel');
\define('_MI_MODULEBUILDER_CONFIG_DISPLAY_USER_SIDE', 'Visible in User side');
// Added in version 1.91
\define('_MI_MODULEBUILDER_CONFIG_ACTIVE_BLOCKS', 'Allow Blocks');
// ---------------------
\define('_MI_MODULEBUILDER_CONFIG_ACTIVE_SEARCH', 'Allow Search');
\define('_MI_MODULEBUILDER_CONFIG_ACTIVE_COMMENTS', 'Allow Comments');
\define('_MI_MODULEBUILDER_CONFIG_ACTIVE_NOTIFICATIONS', 'Allow notifications');
\define('_MI_MODULEBUILDER_CONFIG_ACTIVE_PERMISSIONS', 'Allow permissions');
\define('_MI_MODULEBUILDER_CONFIG_INROOT_COPY', 'Copy this module also in root/modules?');
\define('_MI_MODULEBUILDER_CONFIG_PAYPAL_BUTTON', 'Paypal Button');
\define('_MI_MODULEBUILDER_CONFIG_SUBVERSION', 'Subversion');
\define('_MI_MODULEBUILDER_CONFIG_DESCRIPTION', 'Module Description');
\define('_MI_MODULEBUILDER_CONFIG_MIMETYPES_IMAGE', 'Mimetypes of images');
\define('_MI_MODULEBUILDER_CONFIG_MIMETYPES_IMAGE_DESC', 'Set mimetypes of images and files separated by <b>|</b>');
\define('_MI_MODULEBUILDER_CONFIG_MAXSIZE_IMAGE', 'Maximum size of images');
\define('_MI_MODULEBUILDER_CONFIG_MAXSIZE_IMAGE_DESC', 'Set maximum size of images in Bytes');
//1.39
\define('_MI_MODULEBUILDER_CONFIG_BREAK_GENERAL', 'General preferences');
\define('_MI_MODULEBUILDER_CONFIG_BREAK_REQUIRED', 'Module required');
\define('_MI_MODULEBUILDER_CONFIG_BREAK_OPTIONAL', 'Module optional');
\define('_MI_MODULEBUILDER_CONFIG_MIN_PHP', 'Minimum Php');
\define('_MI_MODULEBUILDER_CONFIG_MIN_XOOPS', 'Minimum Xoops');
\define('_MI_MODULEBUILDER_CONFIG_MIN_ADMIN', 'Minimum Admin');
\define('_MI_MODULEBUILDER_CONFIG_MIN_MYSQL', 'Minimum MySQL');
\define('_MI_MODULEBUILDER_CONFIG_MODULES_ADMINPAGER', 'Modules AdminPager');
\define('_MI_MODULEBUILDER_CONFIG_MODULES_ADMINPAGER_DESC', 'Set how many maximum pages you want to see in Modules');
\define('_MI_MODULEBUILDER_CONFIG_TABLES_ADMINPAGER', 'Tables AdminPager');
\define('_MI_MODULEBUILDER_CONFIG_TABLES_ADMINPAGER_DESC', 'Set how many maximum pages you want to see in Tables');
\define('_MI_MODULEBUILDER_CONFIG_FIELDS_ADMINPAGER', 'Fields AdminPager');
\define('_MI_MODULEBUILDER_CONFIG_FIELDS_ADMINPAGER_DESC', 'Set how many maximum pages you want to see in Fields');
\define('_MI_MODULEBUILDER_CONFIG_MOREFILES_ADMINPAGER', 'More Files AdminPager');
\define('_MI_MODULEBUILDER_CONFIG_MOREFILES_ADMINPAGER_DESC', 'Set how many maximum pages you want to see in Files');
\define('_MI_MODULEBUILDER_CONFIG_SETTINGS_ADMINPAGER', 'Settings AdminPager');
\define('_MI_MODULEBUILDER_CONFIG_SETTINGS_ADMINPAGER_DESC', 'Set how many maximum pages you want to see in Settings');

\define('_AM_MODULEBUILDER_FIELD_ORDER_ERROR', 'Error in Field order');
\define('_AM_MODULEBUILDER_FORM_DELETED_OK', 'Form Deleted');
\define('_AM_MODULEBUILDER_NOT_MODULES', 'No modules');
\define('_AM_MODULEBUILDER_FORM_SURE_DELETE', 'Are you sure to delete it?');
\define('_AM_MODULEBUILDER_TABLE_ORDER_ERROR', 'Error in Table order');

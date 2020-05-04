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
 * tdmcreate module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */
//Menu
define('_AM_TDMCREATE_ADMIN_INDEX', 'Index');
define('_AM_TDMCREATE_ADMIN_MODULES', 'Add Module');
define('_AM_TDMCREATE_ADMIN_TABLES', 'Add Table');
define('_AM_TDMCREATE_ADMIN_CONST', 'Build Module');
define('_AM_TDMCREATE_ADMIN_ABOUT', 'About');
define('_AM_TDMCREATE_ADMIN_PREFERENCES', 'Preferences');
define('_AM_TDMCREATE_ADMIN_UPDATE', 'Update');
define('_AM_TDMCREATE_ADMIN_NUMMODULES', 'Statistics');
define('_AM_TDMCREATE_THEREARE_NUMSETTINGS', "There are <span class='red bold'>%s</span> settings stored in the Database");
define('_AM_TDMCREATE_THEREARE_NUMMODULES', "There are <span class='red bold'>%s</span> modules stored in the Database");
define('_AM_TDMCREATE_THEREARE_NUMTABLES', "There are <span class='red bold'>%s</span> tables stored in the Database");
define('_AM_TDMCREATE_THEREARE_NUMFIELDS', "There are <span class='red bold'>%s</span> fields stored in the Database");
define('_AM_TDMCREATE_THEREARE_NUMFILES', "There are <span class='red bold'>%s</span> more files stored in the Database");
// General
define('_AM_TDMCREATE_FORMOK', 'Successfully saved');
define('_AM_TDMCREATE_FORMDELOK', 'Successfully deleted');
define('_AM_TDMCREATE_FORMSUREDEL', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
define('_AM_TDMCREATE_FORMSURERENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
define('_AM_TDMCREATE_FORMUPLOAD', 'Upload file');
define('_AM_TDMCREATE_FORMIMAGE_PATH', 'Files in %s ');
define('_AM_TDMCREATE_FORMACTION', 'Action');
define('_AM_TDMCREATE_FORMEDIT', 'Modification');
define('_AM_TDMCREATE_FORMDEL', 'Clear');
define('_AM_TDMCREATE_FORMFIELDS', 'Edit fields');
define('_AM_TDMCREATE_FORM_INFO_TABLE_OPTIONAL_FIELD', 'Optional fields');
define('_AM_TDMCREATE_FORM_INFO_TABLE_STRUCTURES_FIELD', 'Structures fields');
define('_AM_TDMCREATE_FORM_INFO_TABLE_ICON_FIELD', 'Icon fields');

define('_AM_TDMCREATE_ID', 'ID');
define('_AM_TDMCREATE_NAME', 'Name');
define('_AM_TDMCREATE_BLOCKS', 'Blocks');
define('_AM_TDMCREATE_NB_FIELDS', 'Number of fields');
define('_AM_TDMCREATE_IMAGE', 'Image');
define('_AM_TDMCREATE_DISPLAY_ADMIN', 'Visible in Admin Panel');
// 1.37
define('_AM_TDMCREATE_DISPLAY_USER', 'Visible in User View');
// Added in version 1.91
define('_AM_TDMCREATE_ADD_SETTING', 'Add Settings');
define('_AM_TDMCREATE_SETTINGS_LIST', 'Settings List');
define('_AM_TDMCREATE_SETTING_NEW', 'New Settings');
define('_AM_TDMCREATE_SETTING_EDIT', 'Edit Settings');
define('_AM_TDMCREATE_SETTING_NAME', 'Set Name');
define('_AM_TDMCREATE_SETTING_DIRNAME', 'Set Directory Name');
define('_AM_TDMCREATE_SETTING_VERSION', 'Set Version');
define('_AM_TDMCREATE_SETTING_SINCE', 'Set Since');
define('_AM_TDMCREATE_SETTING_DESCRIPTION', 'Set Description');
define('_AM_TDMCREATE_SETTING_CREATENEWLOGO', 'Set Create New Logo');
define('_AM_TDMCREATE_SETTING_AUTHOR', 'Set Author');
define('_AM_TDMCREATE_SETTING_AUTHOR_MAIL', 'Set Author Email');
define('_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_URL', 'Set Author Site Url');
define('_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_NAME', 'Set Author Site Name');
define('_AM_TDMCREATE_SETTING_CREDITS', 'Set Credits');
define('_AM_TDMCREATE_SETTING_LICENSE', 'Set License');
define('_AM_TDMCREATE_SETTING_RELEASE_INFO', 'Set Release Info');
define('_AM_TDMCREATE_SETTING_RELEASE_FILE', 'Set Release File');
define('_AM_TDMCREATE_SETTING_MANUAL', 'Set Manual');
define('_AM_TDMCREATE_SETTING_MANUAL_FILE', 'Set Manual File');
define('_AM_TDMCREATE_SETTING_IMAGE', 'Set Image');
define('_AM_TDMCREATE_SETTING_DEMO_SITE_URL', 'Set Demo Site Url');
define('_AM_TDMCREATE_SETTING_DEMO_SITE_NAME', 'Set Demo Site Name');
define('_AM_TDMCREATE_SETTING_SUPPORT_URL', 'Set Support URL');
define('_AM_TDMCREATE_SETTING_SUPPORT_NAME', 'Set Support Name');
define('_AM_TDMCREATE_SETTING_WEBSITE_URL', 'Set Module Website URL');
define('_AM_TDMCREATE_SETTING_WEBSITE_NAME', 'Set Module Website Name');
define('_AM_TDMCREATE_SETTING_RELEASE', 'Set Release');
define('_AM_TDMCREATE_SETTING_STATUS', 'Set Status');
define('_AM_TDMCREATE_SETTING_PAYPAL_BUTTON', 'Set Button for Donations');
define('_AM_TDMCREATE_SETTING_SUBVERSION', 'Set Subversion module');
define('_AM_TDMCREATE_SETTING_ADMIN', 'Set Visible Admin');
define('_AM_TDMCREATE_SETTING_USER', 'Set Visible User');
define('_AM_TDMCREATE_SETTING_MIN_PHP', 'Set Minimum PHP');
define('_AM_TDMCREATE_SETTING_MIN_XOOPS', 'Set Minimum XOOPS');
define('_AM_TDMCREATE_SETTING_MIN_ADMIN', 'Set Minimum Admin');
define('_AM_TDMCREATE_SETTING_MIN_MYSQL', 'Set Minimum MySQL');
define('_AM_TDMCREATE_SETTING_BLOCKS', 'Set Activate Blocks');
define('_AM_TDMCREATE_SETTING_SEARCH', 'Set Activate Search');
define('_AM_TDMCREATE_SETTING_COMMENTS', 'Set Activate Comments');
define('_AM_TDMCREATE_SETTING_NOTIFICATIONS', 'Set Activate Notifications');
define('_AM_TDMCREATE_SETTING_PERMISSIONS', 'Set Activate Permissions');
//define('_AM_TDMCREATE_SETTING_INROOT_COPY', 'Set Create Copy of this module in root/modules');
define('_AM_TDMCREATE_SETTING_ALL', 'Set Check All');
define('_AM_TDMCREATE_SETTING_CHOISE', 'Set Choise Settings');
//Modules.php
//Buttons
define('_AM_TDMCREATE_ADD_MODULE', 'Add new module');
//Form
define('_AM_TDMCREATE_MODULE_NEW', 'New module');
define('_AM_TDMCREATE_MODULE_EDIT', 'Edit module');

define('_AM_TDMCREATE_MODULE_IMPORTANT', "<span style='color: #FF0000;'>Required - Information</span>");
define('_AM_TDMCREATE_MODULE_NOTIMPORTANT', "<span style='color: #00FF00;'>Optional - Information</span>");
define('_AM_TDMCREATE_MODULE_ID', 'Id');
define('_AM_TDMCREATE_MODULE_NAME', 'Name');
define(
    '_AM_TDMCREATE_MODULE_NAME_DESC',
    "The module name can contain spaces and special characters such as accents.<br />
An example would be: <b class='red'>My Simple Module</b>"
);
// Added in version 1.91
define('_AM_TDMCREATE_MODULE_DIRNAME', 'Directory Name');
// ---------------------
define(
    '_AM_TDMCREATE_MODULE_DIRNAME_DESC',
    "The module directory can not contain spaces or special characters such as accents.<br />
An example would be: <b class='red'>mysimplemodule</b>.<br />In case you write the module directory with uppercase characters, they are replaced automatically with lowercase, and if there are spaces they will also be automatically deleted."
);
define('_AM_TDMCREATE_MODULE_VERSION', 'Version');
define('_AM_TDMCREATE_MODULE_SINCE', 'Since');
define('_AM_TDMCREATE_MODULE_DESCRIPTION', 'Description');
define('_AM_TDMCREATE_MODULE_CREATENEWLOGO', 'Create New Logo');
define('_AM_TDMCREATE_MODULE_AUTHOR', 'Author');
define('_AM_TDMCREATE_MODULE_AUTHOR_MAIL', 'Author Email');
define('_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_URL', 'Author Site Url');
define('_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_NAME', 'Author Site Name');
define('_AM_TDMCREATE_MODULE_CREDITS', 'Credits');
define('_AM_TDMCREATE_MODULE_LICENSE', 'License');
define('_AM_TDMCREATE_MODULE_RELEASE_INFO', 'Release Info');
define('_AM_TDMCREATE_MODULE_RELEASE_FILE', 'Release File');
define('_AM_TDMCREATE_MODULE_MANUAL', 'Manual');
define('_AM_TDMCREATE_MODULE_MANUAL_FILE', 'Manual File');
define('_AM_TDMCREATE_MODULE_IMAGE', 'Image');
define('_AM_TDMCREATE_MODULE_DEMO_SITE_URL', 'Demo Site Url');
define('_AM_TDMCREATE_MODULE_DEMO_SITE_NAME', 'Demo Site Name');
define('_AM_TDMCREATE_MODULE_SUPPORT_URL', 'Support URL');
define('_AM_TDMCREATE_MODULE_SUPPORT_NAME', 'Support Name');
define('_AM_TDMCREATE_MODULE_WEBSITE_URL', 'Module Website URL');
define('_AM_TDMCREATE_MODULE_WEBSITE_NAME', 'Module Website Name');
define('_AM_TDMCREATE_MODULE_RELEASE', 'Release');
define('_AM_TDMCREATE_MODULE_STATUS', 'Status');
define('_AM_TDMCREATE_MODULE_PAYPAL_BUTTON', 'Button for Donations');
define('_AM_TDMCREATE_MODULE_SUBVERSION', 'Subversion module');
define('_AM_TDMCREATE_MODULE_ADMIN', 'Visible Admin');
define('_AM_TDMCREATE_MODULE_USER', 'Visible User');
// Added in version 1.91
define('_AM_TDMCREATE_MODULE_BLOCKS', 'Activate Blocks');
define('_AM_TDMCREATE_MODULE_ALL', 'Check All');
define(
    '_AM_TDMCREATE_OPTIONS_DESC',
    "<b>Select one or all items to add specific addon in this new module</b>"
);
// ---------------------
define('_AM_TDMCREATE_MODULE_SEARCH', 'Activate Search');
define('_AM_TDMCREATE_MODULE_COMMENTS', 'Activate Comments');
define('_AM_TDMCREATE_MODULE_NOTIFICATIONS', 'Activate Notifications');
define('_AM_TDMCREATE_MODULE_PERMISSIONS', 'Activate Permissions');
define('_AM_TDMCREATE_MODULE_INROOT_COPY', "Create copy of this module in root/modules<br /><b class='red'>Pay attention: if yes, then an existing module with same name will be overwritten irreversible!</b>");
// Added in version 1.39
define('_AM_TDMCREATE_MODULE_NAME_LIST', 'Name');
define('_AM_TDMCREATE_MODULE_IMAGE_LIST', 'Image');
define('_AM_TDMCREATE_MODULE_NBFIELDS_LIST', 'Fields');
define('_AM_TDMCREATE_MODULE_BLOCKS_LIST', 'Blocks');
define('_AM_TDMCREATE_MODULE_ADMIN_LIST', 'Admin');
define('_AM_TDMCREATE_MODULE_USER_LIST', 'User');
define('_AM_TDMCREATE_MODULE_SUBMENU_LIST', 'Submenu');
define('_AM_TDMCREATE_MODULE_SEARCH_LIST', 'Search');
define('_AM_TDMCREATE_MODULE_COMMENTS_LIST', 'Comments');
define('_AM_TDMCREATE_MODULE_NOTIFICATIONS_LIST', 'Notifications');
define('_AM_TDMCREATE_MODULE_PERMISSIONS_LIST', 'Permissions');
define('_AM_TDMCREATE_MODULE_MIN_PHP', 'Minimum PHP');
define('_AM_TDMCREATE_MODULE_MIN_XOOPS', 'Minimum XOOPS');
define('_AM_TDMCREATE_MODULE_MIN_ADMIN', 'Minimum Admin');
define('_AM_TDMCREATE_MODULE_MIN_MYSQL', 'Minimum Database');
// Added in version 1.91
define('_AM_TDMCREATE_MODULE_FORM_CREATED_OK', "The module <b class='green'>%s</b> is successfully created");
define('_AM_TDMCREATE_MODULE_FORM_UPDATED_OK', "The module <b class='green'>%s</b> is successfully updated");
// ------------------- Tables --------------------------------- //
//Tables.php
// Buttons
define('_AM_TDMCREATE_ADD_TABLE', 'Add new table');
//Form1
define('_AM_TDMCREATE_TABLE_NEW', 'New Table');
define('_AM_TDMCREATE_TABLE_EDIT', 'Edit Table');
define('_AM_TDMCREATE_TABLE_MODULES', 'Choose a module');
define('_AM_TDMCREATE_TABLE_NAME', 'Table Name');
define('_AM_TDMCREATE_TABLE_NAME_DESC', "Unique Name: It's recommended to use plural word (i.e.: <span style='text-decoration: underline;'>categories</span><span class='white bold'>s</span>)");
// Added in version 1.91
define('_AM_TDMCREATE_TABLE_SOLENAME', 'Table Singular Name');
define('_AM_TDMCREATE_TABLE_SOLENAME_DESC', "Singular  Name: It's recommended to use singular word (i.e.: <span style='text-decoration: underline;'>category</span> for admin buttons)");

define('_AM_TDMCREATE_TABLE_CATEGORY', 'This table is a category or topic?');
define('_AM_TDMCREATE_TABLE_CATEGORY_DESC', "<b class='red bold'>WARNING</b>: <i>Once you have used this option for this module, and edit this table,<br />will not be displayed following the creation of other tables</i>");
define('_AM_TDMCREATE_TABLE_NBFIELDS', 'Number fields');
define('_AM_TDMCREATE_TABLE_NBFIELDS_DESC', 'Number of fields for this table');
define('_AM_TDMCREATE_TABLE_ORDER', 'Order tables');
define('_AM_TDMCREATE_TABLE_ORDER_DESC', 'You should order the tables to view them in the right ordered on the menu and index page of your new module');
define('_AM_TDMCREATE_TABLE_FIELDNAME', 'Prefix Field Name');
define(
    '_AM_TDMCREATE_TABLE_FIELDNAME_DESC',
    "This is the prefix of field name (optional)<br />If you leave the field blank, doesn't appear anything in the fields of the next screen,<br />otherwise you'll see all the fields with a prefix type (i.e.: <span class='bold'>cat</span> of table <span class='bold'>categories</span>).<br /><b class='red bold'>WARNING</b>: It's recommended to use singolar word"
);
define(
    '_AM_TDMCREATE_TABLE_OPTIONS_CHECKS_DESC',
    'For each table created, a file is created on behalf of this.<br />
Selecting one or more of these options, deciding whether to enter the name of the file to other files or you define a condition in these other files, need to be created or not.'
);
define('_AM_TDMCREATE_TABLE_ALL', 'Check All');
define('_AM_TDMCREATE_TABLE_IMAGE', 'Table Logo');
//define('_AM_TDMCREATE_TABLE_IMAGE_DESC', "You can choose an image from the list, or upload a new one from your computer");
// Added in version 1.91
define('_AM_TDMCREATE_TABLE_AUTO_INCREMENT', ' Auto Increment');
define('_AM_TDMCREATE_TABLE_AUTO_INCREMENT_OPTION', 'Default checked');
define('_AM_TDMCREATE_TABLE_AUTO_INCREMENT_DESC', 'Check this option if table have the Auto Increment ID');
// v1.59
define('_AM_TDMCREATE_TABLE_EXIST', 'The name specified for this table is already in use');
define('_AM_TDMCREATE_TABLE_BLOCKS', 'Add in Block Files');
define('_AM_TDMCREATE_TABLE_BLOCKS_DESC', '(blocks: random, latest, today)');
define('_AM_TDMCREATE_TABLE_ADMIN', 'Add in Admin Files');
define('_AM_TDMCREATE_TABLE_USER', 'Add in User Files');
define('_AM_TDMCREATE_TABLE_SUBMENU', 'Add in Submenu');
define('_AM_TDMCREATE_TABLE_SEARCH', 'Add in Search Files');
define('_AM_TDMCREATE_TABLE_COMMENTS', 'Add in Comments Files');
// Added in version 1.39
define('_AM_TDMCREATE_TABLE_NOTIFICATIONS', 'Add in Notifications file');
define('_AM_TDMCREATE_TABLE_PERMISSIONS', 'Add in Permissions file');
// Added in version 1.91
define('_AM_TDMCREATE_TABLE_INSTALL', 'Add in Install file');
define('_AM_TDMCREATE_TABLE_INDEX', 'Add in User Index file');
define('_AM_TDMCREATE_TABLE_SUBMIT', 'Add in Submit file');
define('_AM_TDMCREATE_TABLE_TAG', 'Add in Tag file');
define('_AM_TDMCREATE_TABLE_BROKEN', 'Add in Broken file');
define('_AM_TDMCREATE_TABLE_RATE', 'Add in Rate file');
define('_AM_TDMCREATE_TABLE_PRINT', 'Add in Print file');
define('_AM_TDMCREATE_TABLE_PDF', 'Add in Pdf file');
define('_AM_TDMCREATE_TABLE_RSS', 'Add in Rss file');
define('_AM_TDMCREATE_TABLE_SINGLE', 'Add in Single file');
define('_AM_TDMCREATE_TABLE_VISIT', 'Add in Visit file');
// v1.38
define(
    '_AM_TDMCREATE_TABLE_IMAGE_DESC',
    "<span class='red bold'>WARNING</span>: If you want to choose a new image, is best to name it with the module name before and follow with the name of the image so as not to overwrite any images with the same name, in the <span class='bold'>Frameworks/moduleclasses/moduleadmin/icons/32/</span>. Otherwise an other solution, would be to insert the images in the module, a new folder is created, with the creation of the same module - <span class='bold'>assets/icons/32</span>."
);
define('_AM_TDMCREATE_TABLE_FORM_CREATED_OK', "The table <b class='green'>%s</b> is successfully created");
define('_AM_TDMCREATE_TABLE_FORM_UPDATED_OK', "The table <b class='green'>%s</b> is successfully updated");
// ------------------ Form Fields ------------------
// Caption
define('_AM_TDMCREATE_FIELDS_NEW', 'New fields');
define('_AM_TDMCREATE_FIELDS_EDIT', 'Edit fields');
// Fields
define('_AM_TDMCREATE_FIELD_ID', 'N&#176;');
define('_AM_TDMCREATE_FIELD_NAME', 'Field Name');
define('_AM_TDMCREATE_FIELD_TYPE', 'Type');
define('_AM_TDMCREATE_FIELD_VALUE', 'Value');
define('_AM_TDMCREATE_FIELD_ATTRIBUTE', 'Attribute');
define('_AM_TDMCREATE_FIELD_NULL', 'Null');
define('_AM_TDMCREATE_FIELD_DEFAULT', 'Default');
define('_AM_TDMCREATE_FIELD_KEY', 'Key');
// Fields Parameters
define('_AM_TDMCREATE_FIELD_PARAMETERS', 'Parameters');
define('_AM_TDMCREATE_FIELD_ELEMENTS', 'Options Elements');
define('_AM_TDMCREATE_FIELD_ELEMENT', 'Form Element');
define('_AM_TDMCREATE_FIELD_ELEMENT_NAME', 'Form: Element');
define('_AM_TDMCREATE_FIELD_ADMIN', 'Admin: In Files');
define('_AM_TDMCREATE_FIELD_USER', 'User: In Files');
define('_AM_TDMCREATE_FIELD_BLOCK', 'Block: In Files');
define('_AM_TDMCREATE_FIELD_MAIN', 'Table: Main Field');
define('_AM_TDMCREATE_FIELD_SEARCH', 'Search: Index');
define('_AM_TDMCREATE_FIELD_REQUIRED', 'Field: Required');
define('_AM_TDMCREATE_ADMIN_SUBMIT', 'Send');
// Added in version 1.91
define('_AM_TDMCREATE_FIELD_THEAD', 'User: In Thead');
define('_AM_TDMCREATE_FIELD_TBODY', 'User: In Tbody');
define('_AM_TDMCREATE_FIELD_TFOOT', 'User: In Tfoot');
define('_AM_TDMCREATE_FIELD_RECOMMENDED', 'It is recommended to create following fields:<br>
- %s_date: for sorting items by date it is necessary to have a field, where date of creation/relevant date is stored<br>
- %s_submitter: for sorting items by submitter it is necessary to have a field, where user of creation is stored<br><br>');
// List in templates
define('_AM_TDMCREATE_ID_LIST', 'Id');
define('_AM_TDMCREATE_NAME_LIST', 'Name');
define('_AM_TDMCREATE_IMAGE_LIST', 'Image');
define('_AM_TDMCREATE_NBFIELDS_LIST', 'Fields');
define('_AM_TDMCREATE_PARENT_LIST', 'Parent');
define('_AM_TDMCREATE_INLIST_LIST', 'Inlist');
define('_AM_TDMCREATE_INFORM_LIST', 'Inform');
define('_AM_TDMCREATE_ADMIN_LIST', 'Admin');
define('_AM_TDMCREATE_USER_LIST', 'User');
define('_AM_TDMCREATE_BLOCK_LIST', 'Block');
define('_AM_TDMCREATE_MAIN_LIST', 'Main');
define('_AM_TDMCREATE_SEARCH_LIST', 'Search');
define('_AM_TDMCREATE_REQUIRED_LIST', 'Required');

// building.php
define('_AM_TDMCREATE_CONST_MODULES', 'Select the module you want to build');
define('_AM_TDMCREATE_CONST_TABLES', 'Select the table you want to build');

//------------ new additions: Ver. 1.11 -----------------------

define('_AM_TDMCREATE_ADMIN_PERMISSIONS', 'Permissions');
define('_AM_TDMCREATE_FORMON', 'Online');
define('_AM_TDMCREATE_FORMOFF', 'Offline');

define('_AM_TDMCREATE_TRANSLATION_PERMISSIONS_ACCESS', 'Allowed to access');
define('_AM_TDMCREATE_TRANSLATION_PERMISSIONS_SUBMIT', 'Allowed to post');

define('_AM_TDMCREATE_THEREARE_DATABASE1', "There are <span style='color: #ff0000; font-weight: bold;'>%s</span>");
define('_AM_TDMCREATE_THEREARE_DATABASE2', 'in the database');
define('_AM_TDMCREATE_THEREARE_PENDING', "There are <span style='color: #ff0000; font-weight: bold;'>%s</span>");
define('_AM_TDMCREATE_THEREARE_PENDING2', 'waiting');

define('_AM_TDMCREATE_FORMADD', 'Add');

define('_AM_TDMCREATE_MIMETYPES', 'Mime types authorized for:');
define('_AM_TDMCREATE_MIMESIZE', 'Allowable size:');
define('_AM_TDMCREATE_EDITOR', 'Editor:');

//------------ new additions: Ver. 1.15 -----------------------

define('_AM_TDMCREATE_ABOUT_WEBSITE_FORUM', 'Forum Web Site');

//------------ new additions: Ver. 1.37 -----------------------
define('_AM_TDMCREATE_MODULES_LIST', 'Module List');
define('_AM_TDMCREATE_MODULES_NEW', 'New Module');
define('_AM_TDMCREATE_TABLES_LIST', 'Tables List');
define('_AM_TDMCREATE_TABLES_NEW', 'New Table');
define('_AM_TDMCREATE_TABLES_NEW_CATEGORY', 'New Category');
define('_AM_TDMCREATE_FIELDS_LIST', 'Fields List');

//1.38
define('_AM_TDMCREATE_TABLES_STATUS', 'Show Table Status');
define('_AM_TDMCREATE_TABLES_WAITING', 'Show Table Waiting');

//1.39
define('_AM_TDMCREATE_MODULES_MIN_PHP', 'Minimum PHP');
define('_AM_TDMCREATE_MODULES_MIN_XOOPS', 'Minimum XOOPS');
define('_AM_TDMCREATE_MODULES_MIN_ADMIN', 'Minimum Admin');
define('_AM_TDMCREATE_MODULES_MIN_MYSQL', 'Minimum Database');
define('_AM_TDMCREATE_BUILDING_FILES', 'Files that have been compiled');
define('_AM_TDMCREATE_BUILDING_SUCCESS', 'Success build');
define('_AM_TDMCREATE_BUILDING_FAILED', 'Failed build');
define('_AM_TDMCREATE_CONST_OK_ARCHITECTURE_ROOT', 'The structure of the module was created in root/modules (index.html, folders, ...)');
define('_AM_TDMCREATE_CONST_NOTOK_ARCHITECTURE_ROOT', 'Problems: Creating the structure of the module in root/modules (index.html, icons ,...)');

// Added in version 1.59
define('_AM_TDMCREATE_TABLE_ID', 'Id');
define('_AM_TDMCREATE_TABLE_NAME_LIST', 'Name');
define('_AM_TDMCREATE_TABLE_IMAGE_LIST', 'Table Icon');
define('_AM_TDMCREATE_TABLE_NBFIELDS_LIST', 'Fields');
define('_AM_TDMCREATE_TABLE_BLOCKS_LIST', 'Blocks');
define('_AM_TDMCREATE_TABLE_ADMIN_LIST', 'Admin');
define('_AM_TDMCREATE_TABLE_USER_LIST', 'User');
define('_AM_TDMCREATE_TABLE_SUBMENU_LIST', 'Submenu');
define('_AM_TDMCREATE_TABLE_SEARCH_LIST', 'Search');
define('_AM_TDMCREATE_TABLE_COMMENTS_LIST', 'Comments');
define('_AM_TDMCREATE_TABLE_NOTIFICATIONS_LIST', 'Notifications');
define('_AM_TDMCREATE_TABLE_PERMISSIONS_LIST', 'Permissions');
define('_AM_TDMCREATE_EDIT_TABLE', 'Edit Table');
define('_AM_TDMCREATE_EDIT_FIELDS', 'Edit Fields');
define('_AM_TDMCREATE_BUILD_INROOT', 'Do you want to install this module in the modules root of your site?');
define(
    '_AM_TDMCREATE_BUILD_INROOT_DESC',
    "<b class='red big'>WARNING</b>: If in the modules directory of your site is installed a module with the same name,<br />as the one you are about to create now, this will be erased with the appropriate consequences.<br />We recommend you to first check, in the root/modules of your site, if this module already exists."
);
//define('_AM_TDMCREATE_MODULE_PERMISSIONS', "Enable permissions");
//define('_AM_TDMCREATE_MODULE_INSTALL', "Install this module directly in root/modules?");

// Added in version 1.91
define('_AM_TDMCREATE_CHANGE_DISPLAY', 'Change Display');
define('_AM_TDMCREATE_CHANGE_SETTINGS', 'Change Settings');
define('_AM_TDMCREATE_TOGGLE_SUCCESS', 'Successfully Changed Display');
define('_AM_TDMCREATE_TOGGLE_FAILED', 'Changing Display Failed');
define('_AM_TDMCREATE_ERROR_TABLE_NAME_EXIST', "<b class='red big'>WARNING</b>: The table <b class='big red'>%s</b> exists for this module, create a new one with a different name");
define('_AM_TDMCREATE_FIELD_PARENT', 'Field: Is parent');
define('_AM_TDMCREATE_FIELD_INLIST', 'Admin: Visible in list');
define('_AM_TDMCREATE_FIELD_INFORM', 'Admin: Visible in form');
define('_AM_TDMCREATE_TABLE_MODSELOPT', 'Select a Module');
define('_AM_TDMCREATE_BUILD_MODSELOPT', 'Select and build a Module');
define('_AM_TDMCREATE_NOTMODULES', "There aren't modules, pleace create one first");
define('_AM_TDMCREATE_NOTTABLES', "There aren't tables, pleace create one first");
define('_AM_TDMCREATE_FIELDS_FORM_SAVED_OK', "Fields of table <b class='green'>%s</b> successfully saved");
define('_AM_TDMCREATE_FIELDS_FORM_UPDATED_OK', "Fields of table <b class='green'>%s</b> successfully updated");

define('_AM_TDMCREATE_THEREARENT_MODULES', "There aren't modules");
define('_AM_TDMCREATE_THEREARENT_TABLES', "There aren't tables");
define('_AM_TDMCREATE_THEREARENT_FIELDS', "There aren't fields");
define('_AM_TDMCREATE_THEREARENT_MORE_FILES', "There aren't more files");
//Creation
define('_AM_TDMCREATE_MODULE_BUTTON_NEW_LOGO', 'Create new Logo');
//OK
define('_AM_TDMCREATE_OK_ARCHITECTURE', "<span class='green'>The structure of the module was created (index.html, folders, icons, docs files)</span>");
define('_AM_TDMCREATE_FILE_CREATED', "The file <b>%s</b> is created in the <span class='green bold'>%s</span> folder");
//NOTOK
define('_AM_TDMCREATE_NOTOK_ARCHITECTURE', "<span class='red'>Problems: Creating the structure of the module (index.html, folders, icons, docs files)</span>");
define('_AM_TDMCREATE_FILE_NOTCREATED', "Problems: Creating file <b class='red'>%s</b> in the <span class='red bold'>%s</span> folder");

define('_AM_TDMCREATE_BUILDING_DIRECTORY', "Files created in the directory <span class='bold'>uploads/tdmcreate/repository/</span> of the module <span class='bold green'>%s</span>");
define('_AM_TDMCREATE_BUILDING_DIRECTORY_INROOT', "<br><span class='bold red'>Created module was also copied to %s</span>");
define('_AM_TDMCREATE_FIELD_PARAMETERS_LIST', '<b>Parameters List</b>');
// Added in version v1.91
define('_AM_TDMCREATE_ABOUT_MAKE_DONATION', 'Make a Donation to support this module');
define('_AM_TDMCREATE_MAINTAINED', '<strong>%s</strong> is maintained by the ');
define('_AM_TDMCREATE_SUPPORT_FORUM', 'Support Forum');
define('_AM_TDMCREATE_DONATION_AMOUNT', 'Donation Amount');

define('_AM_TDMCREATE_IMPORTANT', 'Main Settings');
define('_AM_TDMCREATE_OPTIONS_CHECK', 'Options Settings');
define('_AM_TDMCREATE_CREATE_IMAGE', 'Create Image Logo');
define('_AM_TDMCREATE_NOT_IMPORTANT', 'Secondary Settings');
define('_AM_TDMCREATE_BUILDING_DELETED_CACHE_FILES', 'Cache Files Are Deleted Succefully');
// Admin More Files
define('_AM_TDMCREATE_ADD_MORE_FILE', 'Add More File');
define('_AM_TDMCREATE_MORE_FILES_LIST', 'More Files List');
define('_AM_TDMCREATE_FILE_FORM_CREATED_OK', "The file <b class='green'>%s</b> is successfully created");
define('_AM_TDMCREATE_FILE_FORM_UPDATED_OK', "The file <b class='green'>%s</b> is successfully updated");
// Class More Files
define('_AM_TDMCREATE_MORE_FILES_NEW', 'New More File');
define('_AM_TDMCREATE_MORE_FILES_EDIT', 'Edit More File');
define('_AM_TDMCREATE_MORE_FILES_MODULES', 'Choose a module');
define('_AM_TDMCREATE_MORE_FILES_MODULE_SELECT', 'Select a Module');
define('_AM_TDMCREATE_MORE_FILES_NAME', 'File Name');
define('_AM_TDMCREATE_MORE_FILES_NAME_DESC', 'Create file name without extension');
define('_AM_TDMCREATE_MORE_FILES_EXTENSION', 'Extension File');
define('_AM_TDMCREATE_MORE_FILES_EXTENSION_DESC', 'Create extension of this file without dot');
define('_AM_TDMCREATE_MORE_FILES_INFOLDER', 'File in the folder');
define('_AM_TDMCREATE_MORE_FILES_INFOLDER_DESC', 'Insert this file in a folder (Type: admin, user, class, include, templates, ...)');
// Template More Files
define('_AM_TDMCREATE_FILE_ID', 'Id');
define('_AM_TDMCREATE_FILE_NAME_LIST', 'File Name');
define('_AM_TDMCREATE_FILE_MID_LIST', 'Module Name');
define('_AM_TDMCREATE_FILE_EXTENSION_LIST', 'Extension Type');
define('_AM_TDMCREATE_FILE_INFOLDER_LIST', 'In Folder');
define('_AM_TDMCREATE_FORM_ACTION', 'Action');

define('_AM_TDMCREATE_THEREARENT_SETTINGS', 'There are NO Settings! Please create Settings');
// added Version 3.02
define('_AM_TDMCREATE_BUILDING_COMMON', "Copied common files and created folder for test data");
define('_AM_TDMCREATE_FIELD_ELE_TEXT', "Textbox");
define('_AM_TDMCREATE_FIELD_ELE_TEXTAREA', "TextArea Field");
define('_AM_TDMCREATE_FIELD_ELE_DHTMLTEXTAREA', "Selectable Editor (Dhtml)");
define('_AM_TDMCREATE_FIELD_ELE_CHECKBOX', "CheckBox");
define('_AM_TDMCREATE_FIELD_ELE_RADIOYN', "Radio Yes/No");
define('_AM_TDMCREATE_FIELD_ELE_SELECTBOX', "Select Listbox");
define('_AM_TDMCREATE_FIELD_ELE_SELECTUSER', "Select User Field");
define('_AM_TDMCREATE_FIELD_ELE_COLORPICKER', "Color Picker Field");
define('_AM_TDMCREATE_FIELD_ELE_IMAGELIST', "Image List");
define('_AM_TDMCREATE_FIELD_ELE_SELECTFILE', "Select File Field");
define('_AM_TDMCREATE_FIELD_ELE_URLFILE', "Url File Field");
define('_AM_TDMCREATE_FIELD_ELE_UPLOADIMAGE', "Upload Image Field");
define('_AM_TDMCREATE_FIELD_ELE_UPLOADFILE', "Upload File Field");
define('_AM_TDMCREATE_FIELD_ELE_TEXTDATESELECT', "Date Select Field");
define('_AM_TDMCREATE_FIELD_ELE_SELECTSTATUS', "Select Status Field");
define('_AM_TDMCREATE_FIELD_ELE_PASSWORD', "Password Field");
define('_AM_TDMCREATE_FIELD_ELE_SELECTCOUNTRY', "Select Country List");
define('_AM_TDMCREATE_FIELD_ELE_SELECTLANG', "Select Language List");
define('_AM_TDMCREATE_FIELD_ELE_DATETIME', "Date/Time Select Field");
define('_AM_TDMCREATE_FIELD_ELE_SELECTCOMBO', "Select Combobox");
define('_AM_TDMCREATE_FIELD_ELE_RADIO', "Radio");
define('_AM_TDMCREATE_FIELD_ELE_RADIO_1', "Radio Value 1");
define('_AM_TDMCREATE_FIELD_ELE_RADIO_2', "Radio Value 2");

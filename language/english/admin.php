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
//
//Menu
\define('_AM_MODULEBUILDER_ADMIN_INDEX', 'Index');
\define('_AM_MODULEBUILDER_ADMIN_MODULES', 'Add Module');
\define('_AM_MODULEBUILDER_ADMIN_TABLES', 'Add Table');
\define('_AM_MODULEBUILDER_ADMIN_CONST', 'Build Module');
\define('_AM_MODULEBUILDER_ADMIN_ABOUT', 'About');
\define('_AM_MODULEBUILDER_ADMIN_PREFERENCES', 'Preferences');
\define('_AM_MODULEBUILDER_ADMIN_UPDATE', 'Update');
\define('_AM_MODULEBUILDER_ADMIN_NUMMODULES', 'Statistics');
\define('_AM_MODULEBUILDER_THEREARE_NUMSETTINGS', "There are <span class='red bold'>%s</span> settings stored in the Database");
\define('_AM_MODULEBUILDER_THEREARE_NUMMODULES', "There are <span class='red bold'>%s</span> modules stored in the Database");
\define('_AM_MODULEBUILDER_THEREARE_NUMTABLES', "There are <span class='red bold'>%s</span> tables stored in the Database");
\define('_AM_MODULEBUILDER_THEREARE_NUMFIELDS', "There are <span class='red bold'>%s</span> fields stored in the Database");
\define('_AM_MODULEBUILDER_THEREARE_NUMFILES', "There are <span class='red bold'>%s</span> more files stored in the Database");
\define('_AM_MODULEBUILDER_THEREARENT_MODULES', "There aren't modules");
\define('_AM_MODULEBUILDER_THEREARENT_TABLES', "There aren't tables");
\define('_AM_MODULEBUILDER_THEREARENT_FIELDS', "There aren't fields");
\define('_AM_MODULEBUILDER_THEREARENT_MORE_FILES', "There aren't more files");
\define('_AM_MODULEBUILDER_THEREARENT_SETTINGS', 'There are NO Settings! Please create Settings');
\define('_AM_MODULEBUILDER_THEREARENT_MODULES2', "There aren't modules, pleace create one first");
\define('_AM_MODULEBUILDER_THEREARENT_TABLES2', "There aren't tables, pleace create one first");
//
// General
\define('_AM_MODULEBUILDER_FORMOK', 'Successfully saved');
\define('_AM_MODULEBUILDER_FORMDELOK', 'Successfully deleted');
\define('_AM_MODULEBUILDER_FORMSUREDEL', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
\define('_AM_MODULEBUILDER_FORMSURERENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
\define('_AM_MODULEBUILDER_FORMUPLOAD', 'Upload file');
\define('_AM_MODULEBUILDER_FORMIMAGE_PATH', 'Files in %s ');
\define('_AM_MODULEBUILDER_FORMACTION', 'Action');
\define('_AM_MODULEBUILDER_FORMEDIT', 'Modification');
\define('_AM_MODULEBUILDER_FORMDEL', 'Clear');
\define('_AM_MODULEBUILDER_FORMFIELDS', 'Edit fields');
\define('_AM_MODULEBUILDER_FORM_INFO_TABLE_OPTIONAL_FIELD', 'Optional fields');
\define('_AM_MODULEBUILDER_FORM_INFO_TABLE_STRUCTURES_FIELD', 'Structures fields');
\define('_AM_MODULEBUILDER_FORM_INFO_TABLE_ICON_FIELD', 'Icon fields');
\define('_AM_MODULEBUILDER_FORMON', 'Online');
\define('_AM_MODULEBUILDER_FORMOFF', 'Offline');
\define('_AM_MODULEBUILDER_FORMADD', 'Add');
\define(
    '_AM_MODULEBUILDER_OPTIONS_DESC',
    "<b>Select one or all items to add specific addon in this new module</b>"
);
// Common / List headers in templates
\define('_AM_MODULEBUILDER_ID', 'Id');
\define('_AM_MODULEBUILDER_NAME', 'Name');
\define('_AM_MODULEBUILDER_BLOCK', 'Block');
\define('_AM_MODULEBUILDER_BLOCKS', 'Blocks');
\define('_AM_MODULEBUILDER_FIELDS', 'Fields');
\define('_AM_MODULEBUILDER_NBFIELDS', 'Number of fields');
\define('_AM_MODULEBUILDER_IMAGE', 'Image');
\define('_AM_MODULEBUILDER_DISPLAY_ADMIN', 'Visible in Admin Panel');
\define('_AM_MODULEBUILDER_DISPLAY_USER', 'Visible in User View');
\define('_AM_MODULEBUILDER_PARENT', 'Parent');
\define('_AM_MODULEBUILDER_INLIST', 'Inlist');
\define('_AM_MODULEBUILDER_INFORM', 'Inform');
\define('_AM_MODULEBUILDER_ADMIN', 'Admin');
\define('_AM_MODULEBUILDER_USER', 'User');
\define('_AM_MODULEBUILDER_MAIN', 'Main');
\define('_AM_MODULEBUILDER_SEARCH', 'Search');
\define('_AM_MODULEBUILDER_REQUIRED', 'Required');
\define('_AM_MODULEBUILDER_SUBMENU', 'Submenu');
\define('_AM_MODULEBUILDER_COMMENTS', 'Comments');
\define('_AM_MODULEBUILDER_NOTIFICATIONS', 'Notifications');
\define('_AM_MODULEBUILDER_PERMISSIONS', 'Permissions');
//
// ------------------- Settings --------------------------------- //
// buttons
\define('_AM_MODULEBUILDER_SETTINGS_ADD', 'Add Settings');
\define('_AM_MODULEBUILDER_SETTINGS_LIST', 'Settings List');
// form
\define('_AM_MODULEBUILDER_SETTING_NEW', 'New Settings');
\define('_AM_MODULEBUILDER_SETTING_EDIT', 'Edit Settings');
\define('_AM_MODULEBUILDER_SETTING_NAME', 'Set Name');
\define('_AM_MODULEBUILDER_SETTING_DIRNAME', 'Set Directory Name');
\define('_AM_MODULEBUILDER_SETTING_VERSION', 'Set Version');
\define('_AM_MODULEBUILDER_SETTING_SINCE', 'Set Since');
\define('_AM_MODULEBUILDER_SETTING_DESCRIPTION', 'Set Description');
\define('_AM_MODULEBUILDER_SETTING_CREATENEWLOGO', 'Set Create New Logo');
\define('_AM_MODULEBUILDER_SETTING_AUTHOR', 'Set Author');
\define('_AM_MODULEBUILDER_SETTING_AUTHOR_MAIL', 'Set Author Email');
\define('_AM_MODULEBUILDER_SETTING_AUTHOR_WEBSITE_URL', 'Set Author Site Url');
\define('_AM_MODULEBUILDER_SETTING_AUTHOR_WEBSITE_NAME', 'Set Author Site Name');
\define('_AM_MODULEBUILDER_SETTING_CREDITS', 'Set Credits');
\define('_AM_MODULEBUILDER_SETTING_LICENSE', 'Set License');
\define('_AM_MODULEBUILDER_SETTING_RELEASE_INFO', 'Set Release Info');
\define('_AM_MODULEBUILDER_SETTING_RELEASE_FILE', 'Set Release File');
\define('_AM_MODULEBUILDER_SETTING_MANUAL', 'Set Manual');
\define('_AM_MODULEBUILDER_SETTING_MANUAL_FILE', 'Set Manual File');
\define('_AM_MODULEBUILDER_SETTING_IMAGE', 'Set Image');
\define('_AM_MODULEBUILDER_SETTING_DEMO_SITE_URL', 'Set Demo Site Url');
\define('_AM_MODULEBUILDER_SETTING_DEMO_SITE_NAME', 'Set Demo Site Name');
\define('_AM_MODULEBUILDER_SETTING_SUPPORT_URL', 'Set Support URL');
\define('_AM_MODULEBUILDER_SETTING_SUPPORT_NAME', 'Set Support Name');
\define('_AM_MODULEBUILDER_SETTING_WEBSITE_URL', 'Set Module Website URL');
\define('_AM_MODULEBUILDER_SETTING_WEBSITE_NAME', 'Set Module Website Name');
\define('_AM_MODULEBUILDER_SETTING_RELEASE', 'Set Release');
\define('_AM_MODULEBUILDER_SETTING_STATUS', 'Set Status');
\define('_AM_MODULEBUILDER_SETTING_PAYPAL_BUTTON', 'Set Button for Donations');
\define('_AM_MODULEBUILDER_SETTING_SUBVERSION', 'Set Subversion module');
\define('_AM_MODULEBUILDER_SETTING_ADMIN', 'Set Visible Admin');
\define('_AM_MODULEBUILDER_SETTING_USER', 'Set Visible User');
\define('_AM_MODULEBUILDER_SETTING_MIN_PHP', 'Set Minimum PHP');
\define('_AM_MODULEBUILDER_SETTING_MIN_XOOPS', 'Set Minimum XOOPS');
\define('_AM_MODULEBUILDER_SETTING_MIN_ADMIN', 'Set Minimum Admin');
\define('_AM_MODULEBUILDER_SETTING_MIN_MYSQL', 'Set Minimum MySQL');
\define('_AM_MODULEBUILDER_SETTING_BLOCKS', 'Set Activate Blocks');
\define('_AM_MODULEBUILDER_SETTING_SEARCH', 'Set Activate Search');
\define('_AM_MODULEBUILDER_SETTING_COMMENTS', 'Set Activate Comments');
\define('_AM_MODULEBUILDER_SETTING_NOTIFICATIONS', 'Set Activate Notifications');
\define('_AM_MODULEBUILDER_SETTING_PERMISSIONS', 'Set Activate Permissions');
\define('_AM_MODULEBUILDER_SETTING_INROOT_COPY', 'Set Create Copy of this module in root/modules');
\define('_AM_MODULEBUILDER_SETTING_ALL', 'Set Check All');
\define('_AM_MODULEBUILDER_SETTING_TYPE', 'Set Type Active');
\define('_AM_MODULEBUILDER_SETTING_TYPE_INACTIVE', 'Inactive');
\define('_AM_MODULEBUILDER_SETTING_TYPE_ACTIVE', 'Active');
//
// ------------------- Modules --------------------------------- //
//Buttons
\define('_AM_MODULEBUILDER_MODULES_ADD', 'Add new module');
\define('_AM_MODULEBUILDER_MODULES_LIST', 'Module List');
//Form
\define('_AM_MODULEBUILDER_MODULE_NEW', 'New module');
\define('_AM_MODULEBUILDER_MODULE_EDIT', 'Edit module');
\define('_AM_MODULEBUILDER_MODULE_IMPORTANT', "<span style='color: #FF0000;'>Required - Information</span>");
\define('_AM_MODULEBUILDER_MODULE_NOTIMPORTANT', "<span style='color: #00FF00;'>Optional - Information</span>");
\define('_AM_MODULEBUILDER_MODULE_ID', 'Id');
\define('_AM_MODULEBUILDER_MODULE_NAME', 'Name');
\define('_AM_MODULEBUILDER_MODULE_NAME_DESC',"The module name can contain spaces and special characters such as accents.<br />An example would be: <b class='red'>My Simple Module</b>");
\define('_AM_MODULEBUILDER_MODULE_DIRNAME', 'Directory Name');
\define('_AM_MODULEBUILDER_MODULE_DIRNAME_DESC', "The module directory can not contain spaces or special characters such as accents.<br />An example would be: <b class='red'>mysimplemodule</b>.<br />In case you write the module directory with uppercase characters, they are replaced automatically with lowercase, and if there are spaces they will also be automatically deleted.");
\define('_AM_MODULEBUILDER_MODULE_VERSION', 'Version');
\define('_AM_MODULEBUILDER_MODULE_SINCE', 'Since');
\define('_AM_MODULEBUILDER_MODULE_DESCRIPTION', 'Description');
\define('_AM_MODULEBUILDER_MODULE_CREATENEWLOGO', 'Create New Logo');
\define('_AM_MODULEBUILDER_MODULE_AUTHOR', 'Author');
\define('_AM_MODULEBUILDER_MODULE_AUTHOR_MAIL', 'Author Email');
\define('_AM_MODULEBUILDER_MODULE_AUTHOR_WEBSITE_URL', 'Author Site Url');
\define('_AM_MODULEBUILDER_MODULE_AUTHOR_WEBSITE_NAME', 'Author Site Name');
\define('_AM_MODULEBUILDER_MODULE_CREDITS', 'Credits');
\define('_AM_MODULEBUILDER_MODULE_LICENSE', 'License');
\define('_AM_MODULEBUILDER_MODULE_RELEASE_INFO', 'Release Info');
\define('_AM_MODULEBUILDER_MODULE_RELEASE_FILE', 'Release File');
\define('_AM_MODULEBUILDER_MODULE_MANUAL', 'Manual');
\define('_AM_MODULEBUILDER_MODULE_MANUAL_FILE', 'Manual File');
\define('_AM_MODULEBUILDER_MODULE_IMAGE', 'Image');
\define('_AM_MODULEBUILDER_MODULE_DEMO_SITE_URL', 'Demo Site Url');
\define('_AM_MODULEBUILDER_MODULE_DEMO_SITE_NAME', 'Demo Site Name');
\define('_AM_MODULEBUILDER_MODULE_SUPPORT_URL', 'Support URL');
\define('_AM_MODULEBUILDER_MODULE_SUPPORT_NAME', 'Support Name');
\define('_AM_MODULEBUILDER_MODULE_WEBSITE_URL', 'Module Website URL');
\define('_AM_MODULEBUILDER_MODULE_WEBSITE_NAME', 'Module Website Name');
\define('_AM_MODULEBUILDER_MODULE_RELEASE', 'Release');
\define('_AM_MODULEBUILDER_MODULE_STATUS', 'Status');
\define('_AM_MODULEBUILDER_MODULE_PAYPAL_BUTTON', 'Button for Donations');
\define('_AM_MODULEBUILDER_MODULE_SUBVERSION', 'Subversion module');
\define('_AM_MODULEBUILDER_MODULE_ADMIN', 'Visible Admin');
\define('_AM_MODULEBUILDER_MODULE_USER', 'Visible User');
\define('_AM_MODULEBUILDER_MODULE_BLOCKS', 'Activate Blocks');
\define('_AM_MODULEBUILDER_MODULE_ALL', 'Check All');
\define('_AM_MODULEBUILDER_MODULE_SEARCH', 'Activate Search');
\define('_AM_MODULEBUILDER_MODULE_COMMENTS', 'Activate Comments');
\define('_AM_MODULEBUILDER_MODULE_NOTIFICATIONS', 'Activate Notifications');
\define('_AM_MODULEBUILDER_MODULE_PERMISSIONS', 'Activate Permissions');
\define('_AM_MODULEBUILDER_MODULE_MIN_PHP', 'Minimum PHP');
\define('_AM_MODULEBUILDER_MODULE_MIN_XOOPS', 'Minimum XOOPS');
\define('_AM_MODULEBUILDER_MODULE_MIN_ADMIN', 'Minimum Admin');
\define('_AM_MODULEBUILDER_MODULE_MIN_MYSQL', 'Minimum Database');
//
\define('_AM_MODULEBUILDER_MODULE_FORM_CREATED_OK', "The module <b class='green'>%s</b> is successfully created");
\define('_AM_MODULEBUILDER_MODULE_FORM_UPDATED_OK', "The module <b class='green'>%s</b> is successfully updated");
\define('_AM_MODULEBUILDER_MODULE_BUTTON_NEW_LOGO', 'Create new Logo');
\define('_AM_MODULEBUILDER_MODULE_NOACTSET', 'Error: No active setting found');
//
// ------------------- Tables --------------------------------- //
// buttons
\define('_AM_MODULEBUILDER_TABLES_ADD', 'Add new table');
\define('_AM_MODULEBUILDER_TABLES_LIST', 'Tables List');
\define('_AM_MODULEBUILDER_TABLES_NEW', 'New Table');
\define('_AM_MODULEBUILDER_TABLES_EDIT', 'Edit Table');
// form
\define('_AM_MODULEBUILDER_TABLES_NEW_CATEGORY', 'New Category');
\define('_AM_MODULEBUILDER_TABLE_MODULES', 'Choose a module');
\define('_AM_MODULEBUILDER_TABLE_NAME', 'Table Name');
\define('_AM_MODULEBUILDER_TABLE_NAME_DESC', "Unique Name: It's recommended to use plural word (i.e.: <span style='text-decoration: underline;'>categories</span><span class='white bold'>s</span>)");
\define('_AM_MODULEBUILDER_TABLE_SOLENAME', 'Table Singular Name');
\define('_AM_MODULEBUILDER_TABLE_SOLENAME_DESC', "Singular  Name: It's recommended to use singular word (i.e.: <span style='text-decoration: underline;'>category</span> for admin buttons)");
\define('_AM_MODULEBUILDER_TABLE_CATEGORY', 'This table is a category or topic?');
\define('_AM_MODULEBUILDER_TABLE_CATEGORY_DESC', "<b class='red bold'>WARNING</b>: <i>Once you have used this option for this module, and edit this table,<br />will not be displayed following the creation of other tables</i>");
\define('_AM_MODULEBUILDER_TABLE_NBFIELDS', 'Number fields');
\define('_AM_MODULEBUILDER_TABLE_NBFIELDS_DESC', 'Number of fields for this table');
\define('_AM_MODULEBUILDER_TABLE_ORDER', 'Order tables');
\define('_AM_MODULEBUILDER_TABLE_ORDER_DESC', 'You should order the tables to view them in the right ordered on the menu and index page of your new module');
\define('_AM_MODULEBUILDER_TABLE_FIELDNAME', 'Prefix Field Name');
\define('_AM_MODULEBUILDER_TABLE_FIELDNAME_DESC', "This is the prefix of field name (optional)<br />If you leave the field blank, doesn't appear anything in the fields of the next screen,<br />otherwise you'll see all the fields with a prefix type (i.e.: <span class='bold'>cat</span> of table <span class='bold'>categories</span>).<br /><b class='red bold'>WARNING</b>: It's recommended to use singular word");
\define('_AM_MODULEBUILDER_TABLE_OPTIONS_CHECKS_DESC', 'For each table created during building prcedure the relevant files will be created on behalf of this.<br />Selecting one or more of these options to decide which functions and condition should be implemented in your module for this table.');
\define('_AM_MODULEBUILDER_TABLE_ALL', 'Check All');
\define('_AM_MODULEBUILDER_TABLE_IMAGE', 'Table Logo');
//\define('_AM_MODULEBUILDER_TABLE_IMAGE_DESC', "You can choose an image from the list, or upload a new one from your computer");
\define('_AM_MODULEBUILDER_TABLE_AUTO_INCREMENT', ' Auto Increment');
\define('_AM_MODULEBUILDER_TABLE_AUTO_INCREMENT_OPTION', 'Default checked');
\define('_AM_MODULEBUILDER_TABLE_AUTO_INCREMENT_DESC', 'Check this option if table have the Auto Increment ID');
\define('_AM_MODULEBUILDER_TABLE_EXIST', 'The name specified for this table is already in use');
\define('_AM_MODULEBUILDER_TABLE_BLOCKS', 'Add in Block Files');
\define('_AM_MODULEBUILDER_TABLE_BLOCKS_DESC', '(blocks: random, latest, today)');
\define('_AM_MODULEBUILDER_TABLE_ADMIN', 'Add in Admin Files');
\define('_AM_MODULEBUILDER_TABLE_USER', 'Add in User Files');
\define('_AM_MODULEBUILDER_TABLE_SUBMENU', 'Add in Submenu');
\define('_AM_MODULEBUILDER_TABLE_SEARCH', 'Add Search Function');
\define('_AM_MODULEBUILDER_TABLE_COMMENTS', 'Add Comments Function');
\define('_AM_MODULEBUILDER_TABLE_NOTIFICATIONS', 'Add Notifications Function');
\define('_AM_MODULEBUILDER_TABLE_PERMISSIONS', 'Add Permissions Function');
\define('_AM_MODULEBUILDER_TABLE_INSTALL', 'Add in Install file');
\define('_AM_MODULEBUILDER_TABLE_INDEX', 'Add in User Index file');
\define('_AM_MODULEBUILDER_TABLE_SUBMIT', 'Add Submit Function');
\define('_AM_MODULEBUILDER_TABLE_TAG', 'Add in Tag file');
\define('_AM_MODULEBUILDER_TABLE_BROKEN', 'Add Broken Function');
\define('_AM_MODULEBUILDER_TABLE_RATE', 'Add in Rate file');
\define('_AM_MODULEBUILDER_TABLE_PRINT', 'Add in Print file');
\define('_AM_MODULEBUILDER_TABLE_PDF', 'Add in Pdf file');
\define('_AM_MODULEBUILDER_TABLE_RSS', 'Add in Rss file');
\define('_AM_MODULEBUILDER_TABLE_READS', 'Add Counter Reads Function');
\define('_AM_MODULEBUILDER_TABLE_SINGLE', 'Add in Single file');
\define('_AM_MODULEBUILDER_TABLE_VISIT', 'Add in Visit file');
\define('_AM_MODULEBUILDER_TABLE_IMAGE_DESC', "<span class='red bold'>WARNING</span>: If you want to choose a new image, is best to name it with the module name before and follow with the name of the image so as not to overwrite any images with the same name, in the <span class='bold'>Frameworks/moduleclasses/moduleadmin/icons/32/</span>. Otherwise an other solution, would be to insert the images in the module, a new folder is created, with the creation of the same module - <span class='bold'>assets/icons/32</span>.");
\define('_AM_MODULEBUILDER_TABLE_FORM_CREATED_OK', "The table <b class='green'>%s</b> is successfully created");
\define('_AM_MODULEBUILDER_TABLE_FORM_UPDATED_OK', "The table <b class='green'>%s</b> is successfully updated");
\define('_AM_MODULEBUILDER_TABLE_IMAGE_LIST', 'Table Icon');
\define('_AM_MODULEBUILDER_TABLES_STATUS', 'Show Table Status');
\define('_AM_MODULEBUILDER_TABLES_WAITING', 'Show Table Waiting');
\define('_AM_MODULEBUILDER_TABLE_MODSELOPT', 'Select a Module');
\define('_AM_MODULEBUILDER_TABLE_ERROR_NAME_EXIST', "<b class='red big'>WARNING</b>: The table <b class='big red'>%s</b> exists for this module, create a new one with a different name");
//
// ------------------- Fields --------------------------------- //
// buttons
\define('_AM_MODULEBUILDER_FIELDS_LIST', 'Fields List');
\define('_AM_MODULEBUILDER_FIELDS_NEW', 'New fields');
\define('_AM_MODULEBUILDER_FIELDS_EDIT', 'Edit fields');
// form
\define('_AM_MODULEBUILDER_FIELD_ID', 'N&#176;');
\define('_AM_MODULEBUILDER_FIELD_NAME', 'Field Name');
\define('_AM_MODULEBUILDER_FIELD_TYPE', 'Type');
\define('_AM_MODULEBUILDER_FIELD_VALUE', 'Value');
\define('_AM_MODULEBUILDER_FIELD_ATTRIBUTE', 'Attribute');
\define('_AM_MODULEBUILDER_FIELD_NULL', 'Null');
\define('_AM_MODULEBUILDER_FIELD_DEFAULT', 'Default');
\define('_AM_MODULEBUILDER_FIELD_KEY', 'Key');
\define('_AM_MODULEBUILDER_FIELD_PARAMETERS', 'Parameters');
\define('_AM_MODULEBUILDER_FIELD_PARAMETERS_LIST', '<b>Parameters List</b>');
\define('_AM_MODULEBUILDER_FIELD_ELEMENTS', 'Options Elements');
\define('_AM_MODULEBUILDER_FIELD_ELEMENT', 'Form Element');
\define('_AM_MODULEBUILDER_FIELD_ELEMENT_NAME', 'Form: Element');
\define('_AM_MODULEBUILDER_FIELD_ADMIN', 'Admin: In Files');
\define('_AM_MODULEBUILDER_FIELD_USER', 'User: In Files');
\define('_AM_MODULEBUILDER_FIELD_BLOCK', 'Block: In Files');
\define('_AM_MODULEBUILDER_FIELD_MAIN', 'Table: Main Field');
\define('_AM_MODULEBUILDER_FIELD_SEARCH', 'Search: Index');
\define('_AM_MODULEBUILDER_FIELD_REQUIRED', 'Field: Required');
\define('_AM_MODULEBUILDER_ADMIN_SUBMIT', 'Send');
\define('_AM_MODULEBUILDER_FIELD_IHEAD', 'User index: in header');
\define('_AM_MODULEBUILDER_FIELD_IBODY', 'User index: in body');
\define('_AM_MODULEBUILDER_FIELD_IFOOT', 'User index: in footer');
\define('_AM_MODULEBUILDER_FIELD_THEAD', 'User file: in header');
\define('_AM_MODULEBUILDER_FIELD_TBODY', 'User file: in body');
\define('_AM_MODULEBUILDER_FIELD_TFOOT', 'User file: in footer');
\define('_AM_MODULEBUILDER_FIELD_RECOMMENDED', "It is recommended to create following fields:<br>
- %s_date:      for sorting items by date it is necessary to have a field, where date of creation/relevant date is stored<br>
- %s_submitter: for sorting items by submitter it is necessary to have a field, where user of creation is stored<br> 
- %s_status:    for using e.g. functions for broken items you need a 'Select Status'-field<br><br>");
\define('_AM_MODULEBUILDER_FIELD_PARENT', 'Field: Is parent');
\define('_AM_MODULEBUILDER_FIELD_INLIST', 'Admin: Visible in list');
\define('_AM_MODULEBUILDER_FIELD_INFORM', 'Admin: Visible in form');
\define('_AM_MODULEBUILDER_FIELDS_FORM_SAVED_OK', "Fields of table <b class='green'>%s</b> successfully saved");
\define('_AM_MODULEBUILDER_FIELDS_FORM_UPDATED_OK', "Fields of table <b class='green'>%s</b> successfully updated");
//
// ------------------- Building --------------------------------- //
\define('_AM_MODULEBUILDER_CONST_MODULES', 'Select the module you want to build');
\define('_AM_MODULEBUILDER_CONST_TABLES', 'Select the table you want to build');
\define('_AM_MODULEBUILDER_BUILDING_FILES', 'Files that have been compiled');
\define('_AM_MODULEBUILDER_BUILDING_SUCCESS', 'Success build');
\define('_AM_MODULEBUILDER_BUILDING_FAILED', 'Failed build');
\define('_AM_MODULEBUILDER_CONST_OK_ARCHITECTURE_ROOT', 'The structure of the module was created in root/modules (index.php, folders, ...)');
\define('_AM_MODULEBUILDER_CONST_NOTOK_ARCHITECTURE_ROOT', 'Problems: Creating the structure of the module in root/modules (index.php, icons ,...)');
\define('_AM_MODULEBUILDER_BUILD_MODSELOPT', 'Select and build a Module');
// OK
\define('_AM_MODULEBUILDER_OK_ARCHITECTURE', "<span class='green'>The structure of the module was created (index.php, folders, icons, docs files)</span>");
\define('_AM_MODULEBUILDER_FILE_CREATED', "The file <b>%s</b> is created in the <span class='green bold'>%s</span> folder");
// NOTOK
\define('_AM_MODULEBUILDER_NOTOK_ARCHITECTURE', "<span class='red'>Problems: Creating the structure of the module (index.php, folders, icons, docs files)</span>");
\define('_AM_MODULEBUILDER_FILE_NOTCREATED', "Problems: Creating file <b class='red'>%s</b> in the <span class='red bold'>%s</span> folder");
// others
\define('_AM_MODULEBUILDER_BUILDING_DIRECTORY', "Files created in the directory <span class='bold'>uploads/modulebuilder/repository/</span> of the module <span class='bold green'>%s</span>");
\define('_AM_MODULEBUILDER_BUILDING_DIRECTORY_INROOT', "<br><span class='bold red'>Created module was also copied to %s</span>");
\define('_AM_MODULEBUILDER_BUILDING_COMMON', "Copied common files and created folder for test data");
\define('_AM_MODULEBUILDER_BUILDING_DELETED_CACHE_FILES', 'Cache Files Are Deleted Succefully');
\define('_AM_MODULEBUILDER_BUILDING_INROOT_COPY', "Create copy of this module in root/modules<br /><b class='red'>Pay attention: if yes, then an existing module with same name will be overwritten irreversible!</b>");
\define('_AM_MODULEBUILDER_BUILDING_TEST', 'Save and restore test data of selected module');
\define('_AM_MODULEBUILDER_BUILDING_TEST_DESC', '<br>If you create copy of this module in root/modules then all data of previous module will be deleted.<br>If you select this option, then data of selected module will be stored temporary and copied back as test data to new build module');
\define('_AM_MODULEBUILDER_BUILDING_CHECK', 'Run data check for errors/inconsistencies before building the module');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_RESULT', 'Result of checking data before building the module');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_NOERRORS', 'Congratulations! No errors or warnings found');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_ERROR', 'Error: ');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_ERROR_DESC', 'Errors must be solved');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_WARNING', 'Warning: ');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_WARNING_DESC', 'Warnings should be solved in order to get proper functions');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FOUND', 'Following errors/warning have been found:');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_SOLVE', 'Please solve these problems and try again');
\define('_AM_MODULEBUILDER_BUILDING_CHECK_BROKEN1', "The table '%t' should use broken functionality, but the table do not have a 'Select Status' field, which is obligatory for this function");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS1', "The field '%f' in table '%t' have no params selected, but each field must have minimum one parameter");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS2', "The field '%f' in table '%t' should be used on user side: Param '" . _AM_MODULEBUILDER_FIELD_USER . "' is selected, but no selection is made, where it should be shown ('User index:...' or 'User file:...')");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS3', "The field '%f' in table '%t' should be used on user side, but you made multiple decision where ('header' and/or 'body' and/or 'footer'). It is recommended to select only one section per index or item file");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS4', "The table '%t' should contains field in index file on user side, but no selection is made, which fields (field params 'User index:...')");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS5', "The field '%f' in table '%t' should be used in index file on user side, but option 'Add in User Index Files' in table setting is deactivated)");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS6', "The table '%t' should contains field in user file on user side, but no selection is made, which fields (field params 'User file:...')");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_FIELDS7', "The field '%f' in table '%t' should be used in user file on user side, but option 'Add in User Files' in table setting is deactivated)");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_BLOCK1', "The table '%t' should use block, but no selection is made which fields should be shown in the block");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_BLOCK2', "The table '%t' should use block, but blocks use e.g. function 'new' or 'latest'. For these functions a date field is necessary, but the table doesn't contain any date field");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_COMMENTS1', "The use of comments is activated for tables '%t'. For proper function it is currently only possible to use comments for one table");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_COMMENTS2', "The table '%t' should use comment functionality, but the table do not have a field of type 'Comments Textbox', which is obligatory for this function");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_RATINGS1', "The table '%t' should use rating functionality, but the table do not have a field of type 'Ratings Textbox', which is obligatory for this function");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_RATINGS2', "The table '%t' should use rating functionality, but the table do not have a field of type 'Votes Textbox', which is obligatory for this function");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_READS1', "The table '%t' should use count reads functionality, but the table do not have a field of type 'Reads Textbox', which is obligatory for this function");
\define('_AM_MODULEBUILDER_BUILDING_RATING', "Copied rating files");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_USERPAGE1', "The table '%t' should use submit or rate or broken functionality, but the table is not selected for user files");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_SQL1', "The field '%f' in table '%t' is type DECIMAL, FLOAT or DOUBLE. The value should be '{digits in total},{digits after the decimal point}', e.g. '16,2'");
\define('_AM_MODULEBUILDER_BUILDING_CHECK_SQL2', "The field '%f' in table '%t' is type DECIMAL, FLOAT or DOUBLE. The default value should be related to precision, e.g. '0.00' if value is '16,2'");
//
// ------------------- More Files --------------------------------- //
\define('_AM_MODULEBUILDER_MORE_FILES_ADD', 'Add More File');
\define('_AM_MODULEBUILDER_MORE_FILES_LIST', 'More Files List');
\define('_AM_MODULEBUILDER_FILE_FORM_CREATED_OK', "The file <b class='green'>%s</b> is successfully created");
\define('_AM_MODULEBUILDER_FILE_FORM_UPDATED_OK', "The file <b class='green'>%s</b> is successfully updated");
// Class More Files
\define('_AM_MODULEBUILDER_MORE_FILES_NEW', 'New More File');
\define('_AM_MODULEBUILDER_MORE_FILES_EDIT', 'Edit More File');
\define('_AM_MODULEBUILDER_MORE_FILES_MODULES', 'Choose a module');
\define('_AM_MODULEBUILDER_MORE_FILES_MODULE_SELECT', 'Select a Module');
\define('_AM_MODULEBUILDER_MORE_FILES_NAME', 'File Name');
\define('_AM_MODULEBUILDER_MORE_FILES_NAME_DESC', 'Create file name without extension');
\define('_AM_MODULEBUILDER_MORE_FILES_EXTENSION', 'Extension File');
\define('_AM_MODULEBUILDER_MORE_FILES_EXTENSION_DESC', 'Create extension of this file without dot');
\define('_AM_MODULEBUILDER_MORE_FILES_INFOLDER', 'File in the folder');
\define('_AM_MODULEBUILDER_MORE_FILES_INFOLDER_DESC', 'Insert this file in a folder (Type: admin, user, class, include, templates, ...)');
\define('_AM_MODULEBUILDER_MORE_FILES_TYPE', 'Type');
\define('_AM_MODULEBUILDER_MORE_FILES_TYPE_EMPTY', 'Create empty file');
\define('_AM_MODULEBUILDER_MORE_FILES_TYPE_COPY', 'Copy existing file');
\define('_AM_MODULEBUILDER_MORE_FILES_UPLOAD', 'Existing file');
// Template More Files
\define('_AM_MODULEBUILDER_FILE_ID', 'Id');
\define('_AM_MODULEBUILDER_FILE_NAME_LIST', 'File Name');
\define('_AM_MODULEBUILDER_FILE_MID_LIST', 'Module Name');
\define('_AM_MODULEBUILDER_FILE_TYPE_LIST', 'Type');
\define('_AM_MODULEBUILDER_FILE_EXTENSION_LIST', 'Extension Type');
\define('_AM_MODULEBUILDER_FILE_INFOLDER_LIST', 'In Folder');
\define('_AM_MODULEBUILDER_FILE_UPLOAD_LIST', 'Uploaded file to be copied');
\define('_AM_MODULEBUILDER_FORM_ACTION', 'Action');
//
// ------------------- Field elements --------------------------------- //
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXT', "Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTAREA', "Plain TextArea Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_DHTMLTEXTAREA', "Selectable Editor (Dhtml)");
\define('_AM_MODULEBUILDER_FIELD_ELE_CHECKBOX', "CheckBox");
\define('_AM_MODULEBUILDER_FIELD_ELE_RADIOYN', "Radio Yes/No");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTBOX', "Select Listbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTUSER', "Select User Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_COLORPICKER', "Color Picker Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_IMAGELIST', "Image List");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTFILE', "Select File Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_URLFILE', "Url File Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_UPLOADIMAGE', "Upload Image Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_UPLOADFILE', "Upload File Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTDATESELECT', "Date Select Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTSTATUS', "Select Status Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_PASSWORD', "Password Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTCOUNTRY', "Select Country List");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTLANG', "Select Language List");
\define('_AM_MODULEBUILDER_FIELD_ELE_DATETIME', "Date/Time Select Field");
\define('_AM_MODULEBUILDER_FIELD_ELE_SELECTCOMBO', "Select Combobox");
\define('_AM_MODULEBUILDER_FIELD_ELE_RADIO', "Radio");
\define('_AM_MODULEBUILDER_FIELD_ELE_RADIO_1', "Radio Value 1");
\define('_AM_MODULEBUILDER_FIELD_ELE_RADIO_2', "Radio Value 2");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTUUID', "UUID Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTIP', "IP Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTCOMMENTS', "Comments Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTRATINGS', "Ratings Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTVOTES', "Votes Textbox");
\define('_AM_MODULEBUILDER_FIELD_ELE_TEXTREADS', "Reads Textbox");
//
// ------------------- Misc --------------------------------- //
\define('_AM_MODULEBUILDER_THEREARE_DATABASE1', "There are <span style='color: #ff0000; font-weight: bold;'>%s</span>");
\define('_AM_MODULEBUILDER_THEREARE_DATABASE2', 'in the database');
\define('_AM_MODULEBUILDER_THEREARE_PENDING', "There are <span style='color: #ff0000; font-weight: bold;'>%s</span>");
\define('_AM_MODULEBUILDER_THEREARE_PENDING2', 'waiting');
// 
\define('_AM_MODULEBUILDER_CHANGE_DISPLAY', 'Change Display');
\define('_AM_MODULEBUILDER_CHANGE_SETTINGS', 'Change Settings');
\define('_AM_MODULEBUILDER_TOGGLE_SUCCESS', 'Successfully Changed Display');
\define('_AM_MODULEBUILDER_TOGGLE_FAILED', 'Changing Display Failed');
//
\define('_AM_MODULEBUILDER_ABOUT_WEBSITE_FORUM', 'Forum Web Site');
\define('_AM_MODULEBUILDER_ABOUT_MAKE_DONATION', 'Make a Donation to support this module');
\define('_AM_MODULEBUILDER_MAINTAINED', '<strong>%s</strong> is maintained by the ');
\define('_AM_MODULEBUILDER_SUPPORT_FORUM', 'Support Forum');
\define('_AM_MODULEBUILDER_DONATION_AMOUNT', 'Donation Amount');
\define('_AM_MODULEBUILDER_OPTIONS_CHECK', 'Options Settings');
\define('_AM_MODULEBUILDER_CREATE_IMAGE', 'Create Image Logo');
//
// ------------------- Devtools --------------------------------- //
\define('_AM_MODULEBUILDER_DEVTOOLS', 'Developer Tools');
\define('_AM_MODULEBUILDER_DEVTOOLS_FQ', 'Add function qualifiers to modules');
\define('_AM_MODULEBUILDER_DEVTOOLS_FQ_MODULE', 'Select module to add function qualifiers');
\define('_AM_MODULEBUILDER_DEVTOOLS_FQ_DESC', 'This tool creates a copy of selected module in %s and add function qualifiers to the php functions');
\define('_AM_MODULEBUILDER_DEVTOOLS_FQ_SUCCESS', 'Function qualifiers successfully added to copy of module');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL', 'Check language defines of your modules');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_MODULE', 'Select module to check');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_DESC', 'This tool takes all language defines in folder /language/englisch of your module and check, whether it is used somewhere or not');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_RESULTS', 'Result of checking usage of language defines of your modules');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_FILE', 'File with language defines:');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_LEGEND', 'Legend of results:');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS', 'Language define found');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS_DESCR', '(first match in brackets)');
\define('_AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_FAILED', 'Language define not found');
\define('_AM_MODULEBUILDER_DEVTOOLS_TAB', 'Replace tab by 4 spaces');
\define('_AM_MODULEBUILDER_DEVTOOLS_TAB_MODULE', 'Select module to replace tabs');
\define('_AM_MODULEBUILDER_DEVTOOLS_TAB_DESC', 'This tool creates a copy of selected module in %s and replace tab by 4 spaces');

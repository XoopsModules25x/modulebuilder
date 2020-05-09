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
 * My Module 3 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule3
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

// ---------------- Main ----------------
define('_MA_MYMODULE3_INDEX', 'Home');
define('_MA_MYMODULE3_TITLE', 'My Module 3');
define('_MA_MYMODULE3_DESC', 'This module is for doing following...');
define('_MA_MYMODULE3_INDEX_DESC', "Welcome to the homepage of your new module My Module 3!<br>
As you can see, you have created a page with a list of links at the top to navigate between the pages of your module. This description is only visible on the homepage of this module, the other pages you will see the content you created when you built this module with the module ModuleBuilder, and after creating new content in admin of this module. In order to expand this module with other resources, just add the code you need to extend the functionality of the same. The files are grouped by type, from the header to the footer to see how divided the source code.<br><br>If you see this message, it is because you have not created content for this module. Once you have created any type of content, you will not see this message.<br><br>If you liked the module ModuleBuilder and thanks to the long process for giving the opportunity to the new module to be created in a moment, consider making a donation to keep the module ModuleBuilder and make a donation using this button <a href='http://www.txmodxoops.org/modules/xdonations/index.php' title='Donation To Txmod Xoops'><img src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' alt='Button Donations' /></a><br>Thanks!<br><br>Use the link below to go to the admin and create content.");
define('_MA_MYMODULE3_NO_PDF_LIBRARY', 'Libraries TCPDF not there yet, upload them in root/Frameworks');
define('_MA_MYMODULE3_NO', 'No');
// ---------------- Contents ----------------
// Category
define('_MA_MYMODULE3_CATEGORY', 'Category');
define('_MA_MYMODULE3_CATEGORIES', 'Categories');
define('_MA_MYMODULE3_CATEGORIES_TITLE', 'Categories title');
define('_MA_MYMODULE3_CATEGORIES_DESC', 'Categories description');
// Caption of Category
define('_MA_MYMODULE3_CATEGORY_ID', 'Id');
define('_MA_MYMODULE3_CATEGORY_NAME', 'Name');
define('_MA_MYMODULE3_CATEGORY_LOGO', 'Logo');
define('_MA_MYMODULE3_CATEGORY_CREATED', 'Created');
define('_MA_MYMODULE3_CATEGORY_SUBMITTER', 'Submitter');
// Article
define('_MA_MYMODULE3_ARTICLE', 'Article');
define('_MA_MYMODULE3_ARTICLES', 'Articles');
define('_MA_MYMODULE3_ARTICLES_TITLE', 'Articles title');
define('_MA_MYMODULE3_ARTICLES_DESC', 'Articles description');
// Caption of Article
define('_MA_MYMODULE3_ARTICLE_ID', 'Id');
define('_MA_MYMODULE3_ARTICLE_CAT', 'Cat');
define('_MA_MYMODULE3_ARTICLE_TITLE', 'Title');
define('_MA_MYMODULE3_ARTICLE_DESCR', 'Descr');
define('_MA_MYMODULE3_ARTICLE_IMG', 'Img');
define('_MA_MYMODULE3_ARTICLE_ONLINE', 'Online');
define('_MA_MYMODULE3_ARTICLE_FILE', 'File');
define('_MA_MYMODULE3_ARTICLE_CREATED', 'Created');
define('_MA_MYMODULE3_ARTICLE_SUBMITTER', 'Submitter');
// Testfield
define('_MA_MYMODULE3_TESTFIELD', 'Testfield');
define('_MA_MYMODULE3_TESTFIELDS', 'Testfields');
define('_MA_MYMODULE3_TESTFIELDS_TITLE', 'Testfields title');
define('_MA_MYMODULE3_TESTFIELDS_DESC', 'Testfields description');
// Caption of Testfield
define('_MA_MYMODULE3_TESTFIELD_ID', 'Id');
define('_MA_MYMODULE3_TESTFIELD_TEXT', 'Text');
define('_MA_MYMODULE3_TESTFIELD_TEXTAREA', 'Textarea');
define('_MA_MYMODULE3_TESTFIELD_DHTML', 'Dhtml');
define('_MA_MYMODULE3_TESTFIELD_CHECKBOX', 'Checkbox');
define('_MA_MYMODULE3_TESTFIELD_YESNO', 'Yesno');
define('_MA_MYMODULE3_TESTFIELD_SELECTBOX', 'Selectbox');
define('_MA_MYMODULE3_TESTFIELD_USER', 'User');
define('_MA_MYMODULE3_TESTFIELD_COLOR', 'Color');
define('_MA_MYMODULE3_TESTFIELD_IMAGELIST', 'Imagelist');
define('_MA_MYMODULE3_TESTFIELD_URLFILE', 'Urlfile');
define('_MA_MYMODULE3_TESTFIELD_UPLIMAGE', 'Uplimage');
define('_MA_MYMODULE3_TESTFIELD_UPLFILE', 'Uplfile');
define('_MA_MYMODULE3_TESTFIELD_TEXTDATESELECT', 'Textdateselect');
define('_MA_MYMODULE3_TESTFIELD_SELECTFILE', 'Selectfile');
define('_MA_MYMODULE3_TESTFIELD_PASSWORD', 'Password');
define('_MA_MYMODULE3_TESTFIELD_COUNTRY_LIST', 'Country_list');
define('_MA_MYMODULE3_TESTFIELD_LANGUAGE', 'Language');
define('_MA_MYMODULE3_TESTFIELD_RADIO', 'Radio');
define('_MA_MYMODULE3_TESTFIELD_STATUS', 'Status');
define('_MA_MYMODULE3_TESTFIELD_DATETIME', 'Datetime');
define('_MA_MYMODULE3_TESTFIELD_COMBOBOX', 'Combobox');
define('_MA_MYMODULE3_INDEX_THEREARE', 'There are %s Testfields');
define('_MA_MYMODULE3_INDEX_LATEST_LIST', 'Last My Module 3');
// Submit
define('_MA_MYMODULE3_SUBMIT', 'Submit');
define('_MA_MYMODULE3_SUBMIT_TESTFIELD', 'Submit Testfield');
define('_MA_MYMODULE3_SUBMIT_ALLPENDING', 'All testfield/script information are posted pending verification.');
define('_MA_MYMODULE3_SUBMIT_DONTABUSE', 'Username and IP are recorded, so please do not abuse the system.');
define('_MA_MYMODULE3_SUBMIT_ISAPPROVED', 'Your testfield has been approved');
define('_MA_MYMODULE3_SUBMIT_PROPOSER', 'Submit a testfield');
define('_MA_MYMODULE3_SUBMIT_RECEIVED', 'We have received your testfield info. Thank you !');
define('_MA_MYMODULE3_SUBMIT_SUBMITONCE', 'Submit your testfield/script only once.');
define('_MA_MYMODULE3_SUBMIT_TAKEDAYS', 'This will take many days to see your testfield/script added successfully in our database.');
// Form
define('_MA_MYMODULE3_FORM_OK', 'Successfully saved');
define('_MA_MYMODULE3_FORM_DELETE_OK', 'Successfully deleted');
define('_MA_MYMODULE3_FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
define('_MA_MYMODULE3_FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
// Admin link
define('_MA_MYMODULE3_ADMIN', 'Admin');
// ---------------- End ----------------

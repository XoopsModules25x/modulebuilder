<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

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

// Define main template
$templateMain = 'modulebuilder_fields.tpl';

include __DIR__ . '/header.php';
// Recovered value of arguments op in the URL $
$op = \Xmf\Request::getString('op', 'list');
// Get fields Variables
$fieldMid  = \Xmf\Request::getInt('field_mid');
$fieldTid  = \Xmf\Request::getInt('field_tid');
$fieldNumb = \Xmf\Request::getInt('field_numb');
$fieldName = \Xmf\Request::getString('field_name', '');
// switch op
switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('tables_adminpager'));
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/sortable.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_TABLE, 'tables.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        // Redirect if there aren't modules
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_NOTMODULES);
        }
        unset($modulesCount);
        // Redirect if there aren't tables
        $tablesCount = $helper->getHandler('Tables')->getCountTables();
        if (0 == $tablesCount) {
            redirect_header('tables.php?op=new', 2, _AM_MODULEBUILDER_NOTTABLES);
        }
        // Get the list of tables
        $tablesAll = $helper->getHandler('Tables')->getAllTables($start, $limit, 'table_order');
        if ($tablesCount > 0) {
            $tlid = 1;
            foreach (array_keys($tablesAll) as $tid) {
                // Display tables list
                $table = $tablesAll[$tid]->getValuesTables();
                $talid = ['lid' => $tlid];
                $table = array_merge($table, $talid);
                // Get the list of fields
                $fieldsCount = $helper->getHandler('Fields')->getCountFields();
                $fieldsAll   = $helper->getHandler('Fields')->getAllFieldsByModuleAndTableId($table['mid'], $tid);
                // Display fields list
                $fields = [];
                $lid    = 1;
                if ($fieldsCount > 0) {
                    foreach (array_keys($fieldsAll) as $fid) {
                        $field    = $fieldsAll[$fid]->getValuesFields();
                        $falid    = ['lid' => $lid];
                        $fields[] = array_merge($field, $falid);
                        unset($field);
                        ++$lid;
                    }
                }
                ++$tlid;
                unset($lid);
                $table['fields'] = $fields;
                $GLOBALS['xoopsTpl']->append('tables_list', $table);
                unset($table);
            }
            unset($tlid);
            unset($fields);
            if ($tablesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($tablesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_FIELDS);
        }
        break;
    case 'new':
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_LIST, 'tables.php', 'list');
        $adminObject->addItemButton(_AM_MODULEBUILDER_FIELDS_LIST, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Add
        $fieldsObj = $helper->getHandler('Fields')->create();
        $form      = $fieldsObj->getFormNew($fieldMid, $fieldTid, $fieldNumb, $fieldName);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $fieldId = \Xmf\Request::getInt('field_id');
        // Fields Handler
        $fields     = $helper->getHandler('Fields');
        $fieldOrder = 1;
        // Set Variables
        foreach ($_POST['field_id'] as $key => $value) {
            if (isset($value)) {
                $fieldsObj = $fields->get($value);
            } else {
                $fieldsObj = $fields->create();
            }
            $order = $fieldsObj->isNew() ? $fieldOrder : $_GET['field_order'][$key];
            // Set Data
            $fieldsObj->setVar('field_mid', $fieldMid);
            $fieldsObj->setVar('field_tid', $fieldTid);
            $fieldsObj->setVar('field_order', $order);
            $fieldsObj->setVar('field_name', $_POST['field_name'][$key]);
            $fieldsObj->setVar('field_type', $_POST['field_type'][$key]);
            $fieldsObj->setVar('field_value', $_POST['field_value'][$key]);
            $fieldsObj->setVar('field_attribute', $_POST['field_attribute'][$key]);
            $fieldsObj->setVar('field_null', $_POST['field_null'][$key]);
            $fieldsObj->setVar('field_default', $_POST['field_default'][$key]);
            $fieldsObj->setVar('field_key', $_POST['field_key'][$key]);
            $fieldsObj->setVar('field_element', $_POST['field_element'][$key]);
            $fieldsObj->setVar('field_parent', (1 == $_REQUEST['field_parent'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_admin', (1 == $_REQUEST['field_admin'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_inlist', (1 == $_REQUEST['field_inlist'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_inform', (1 == $_REQUEST['field_inform'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_user', (1 == $_REQUEST['field_user'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_thead', (1 == $_REQUEST['field_thead'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_tbody', (1 == $_REQUEST['field_tbody'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_tfoot', (1 == $_REQUEST['field_tfoot'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_block', (1 == $_REQUEST['field_block'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_main', ($key == $_REQUEST['field_main'] ? 1 : 0));
            $fieldsObj->setVar('field_search', (1 == $_REQUEST['field_search'][$key]) ? 1 : 0);
            $fieldsObj->setVar('field_required', (1 == $_REQUEST['field_required'][$key]) ? 1 : 0);
            // Insert Data
            $helper->getHandler('Fields')->insert($fieldsObj);
            ++$fieldOrder;
        }
        unset($fieldOrder);
        // Get table name from field table id
        $tables    = $helper->getHandler('Tables')->get($fieldTid);
        $tableName = $tables->getVar('table_name');
        // Set field elements
        if ($fieldsObj->isNew()) {
            // Redirect to field.php if saved
            redirect_header('fields.php', 2, sprintf(_AM_MODULEBUILDER_FIELDS_FORM_SAVED_OK, $tableName));
        } else {
            // Redirect to field.php if updated - (Needed code from table name by field_tid)
            redirect_header('fields.php', 2, sprintf(_AM_MODULEBUILDER_FIELDS_FORM_UPDATED_OK, $tableName));
        }

        $GLOBALS['xoopsTpl']->assign('error', $fieldsObj->getHtmlErrors());
        $form = $fieldsObj->getFormNew($fieldMid, $fieldTid);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('fields.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_TABLE, 'tables.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_LIST, 'tables.php', 'list');
        $adminObject->addItemButton(_AM_MODULEBUILDER_FIELDS_LIST, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        // Form Edit
        $fieldId   = \Xmf\Request::getInt('field_id');
        $fieldsObj = $helper->getHandler('Fields')->get($fieldId);
        $form      = $fieldsObj->getFormEdit($fieldMid, $fieldTid);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'order':
        // Initialize fields handler
        $fieldsObj = $helper->getHandler('Fields');
        if (isset($_POST['forder'])) {
            $i = 0;
            foreach ($_POST['forder'] as $order) {
                if ($order > 0) {
                    $fieldOrder = $fieldsObj->get($order);
                    $fieldOrder->setVar('field_order', $i);
                    if (!$fieldsObj->insert($fieldOrder)) {
                        $error = true;
                    }
                    ++$i;
                }
            }
            redirect_header('fields.php', 5, _AM_MODULEBUILDER_FIELD_ORDER_ERROR);
            unset($i);
        }
        exit;
        break;
    case 'delete':
        $tablesObj = $helper->getHandler('Tables')->get($fieldTid);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Tables')->delete($tablesObj)) {
                redirect_header('fields.php', 3, _AM_MODULEBUILDER_FORMDELOK);
            } else {
                echo $tablesObj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'field_tid' => $fieldTid, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_MODULEBUILDER_FORMSUREDEL, $tablesObj->getVar('table_name')));
        }
        break;
    case 'display':
        $fieldsArray = ['parent', 'inlist', 'inform', 'admin', 'user', 'block', 'main', 'search', 'required'];
        $fieldId     = \Xmf\Request::getInt('field_id', 0, 'POST');
        if ($fieldId > 0) {
            $fieldsObj = $helper->getHandler('Fields')->get($fieldId);
            foreach ($fieldsArray as $field) {
                if (isset($_POST['field_' . $field])) {
                    $fldField = $fieldsObj->getVar('field_' . $field);
                    $fieldsObj->setVar('field_' . $field, !$fldField);
                }
            }
            if ($helper->getHandler('Fields')->insert($fieldsObj)) {
                redirect_header('fields.php', 3, _AM_MODULEBUILDER_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $fieldsObj->getHtmlErrors());
        }
        break;
}
include __DIR__ . '/footer.php';

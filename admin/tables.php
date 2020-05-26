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

// Define main template
$templateMain = 'modulebuilder_tables.tpl';

include __DIR__ . '/header.php';
// Recovered value of arguments op in the URL $
$op = \Xmf\Request::getString('op', 'list');

$modId = \Xmf\Request::getInt('mod_id');
// Request vars
$tableId         = \Xmf\Request::getInt('table_id');
$tableMid        = \Xmf\Request::getInt('table_mid');
$tableName       = \Xmf\Request::getInt('table_name');
$tableNumbFields = \Xmf\Request::getInt('table_nbfields');
$tableFieldname  = \Xmf\Request::getString('table_fieldname', '');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('modules_adminpager'));
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/sortable.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('tables.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_ADD, 'tables.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        // Get the list of modules
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 10, _AM_MODULEBUILDER_THEREARENT_MODULES2);
        }
        $modulesAll  = $helper->getHandler('Modules')->getAllModules($start, $limit);
        $tablesCount = $helper->getHandler('Tables')->getObjects(null);
        // Redirect if there aren't tables
        if (0 == $tablesCount) {
            redirect_header('tables.php?op=new', 10, _AM_MODULEBUILDER_THEREARENT_TABLES2);
        }
        unset($tablesCount);
        // Display modules list
        if ($modulesCount > 0) {
            foreach (array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValuesModules();
                // Get the list of tables
                $tablesCount = $helper->getHandler('Tables')->getCountTables();
                $tablesAll   = $helper->getHandler('Tables')->getAllTablesByModuleId($i);
                // Display tables list
                $tables = [];
                $lid    = 1;
                if ($tablesCount > 0) {
                    foreach (array_keys($tablesAll) as $t) {
                        $table    = $tablesAll[$t]->getValuesTables();
                        $alid     = ['lid' => $lid];
                        $tables[] = array_merge($table, $alid);
                        unset($table);
                        ++$lid;
                    }
                }
                unset($lid);
                $module['tables'] = $tables;
                $GLOBALS['xoopsTpl']->append('modules_list', $module);
                unset($module);
            }
            if ($modulesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_TABLES);
        }
        break;
    case 'new':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('tables.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_LIST, 'tables.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $tablesObj = $helper->getHandler('Tables')->create();
        if ($tableMid > 0) {
            $tablesObj->setVar('table_mid', $tableMid);
        }
        $form      = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $tables = $helper->getHandler('Tables');
        if (isset($tableId)) {
            $tablesObj = $tables->get($tableId);
        } else {
            // Checking if table name exist in the same module
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('table_mid', $tableMid));
            $tableNameSearch = $tables->getObjects($criteria);
            unset($criteria);
            //unset($criteria);
            foreach (array_keys($tableNameSearch) as $t) {
                if ($tableNameSearch[$t]->getVar('table_name') === \Xmf\Request::getString('table_name', '', 'POST')) {
                    redirect_header('tables.php?op=new', 3, sprintf(_AM_MODULEBUILDER_TABLE_ERROR_NAME_EXIST, \Xmf\Request::getString('table_name', '', 'POST')));
                }
            }
            $tablesObj = $tables->create();
        }
        $tableOrder = \Xmf\Request::getInt('table_order');
        $order      = $tablesObj->isNew() ? $tableOrder + 1 : $tableOrder;
        // Form save tables
        $tablesObj->setVars(
            [
                'table_mid'       => $tableMid,
                'table_name'      => \Xmf\Request::getString('table_name', '', 'POST'),
                'table_solename'  => \Xmf\Request::getString('table_solename', '', 'POST'),
                'table_category'  => (1 == $_REQUEST['table_category']) ? 1 : 0,
                'table_fieldname' => $tableFieldname,
                'table_nbfields'  => $tableNumbFields,
                'table_order'     => $order,
            ]
        );
        //Form table_image
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploaddir = is_dir(XOOPS_ICONS32_PATH) ? XOOPS_ICONS32_PATH : TDMC_UPLOAD_IMGTAB_PATH;
        $uploader  = new \XoopsMediaUploader(
            $uploaddir, $helper->getConfig('mimetypes_image'), $helper->getConfig('maxsize_image'), null, null
        );
        if ($uploader->fetchMedia(\Xmf\Request::getString('xoops_upload_file', '', 'POST')[0])) {
            $uploader->fetchMedia(\Xmf\Request::getString('xoops_upload_file', '', 'POST')[0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $tablesObj->setVar('table_image', $uploader->getSavedFileName());
            }
        } else {
            $tablesObj->setVar('table_image', \Xmf\Request::getString('table_image', '', 'POST'));
        }
        $tablesObj->setVar('table_autoincrement', (1 == $_REQUEST['table_autoincrement']) ? 1 : 0);
        // Options
        $tableOption = \Xmf\Request::getArray('table_option', []);
        $tablesObj->setVar('table_install', in_array('install', $tableOption));
        $tablesObj->setVar('table_index', in_array('index', $tableOption));
        $tablesObj->setVar('table_blocks', in_array('blocks', $tableOption));
        $tablesObj->setVar('table_admin', in_array('admin', $tableOption));
        $tablesObj->setVar('table_user', in_array('user', $tableOption));
        $tablesObj->setVar('table_submenu', in_array('submenu', $tableOption));
        $tablesObj->setVar('table_submit', in_array('submit', $tableOption));
        $tablesObj->setVar('table_tag', in_array('tag', $tableOption));
        $tablesObj->setVar('table_broken', in_array('broken', $tableOption));
        $tablesObj->setVar('table_search', in_array('search', $tableOption));
        $tablesObj->setVar('table_comments', in_array('comments', $tableOption));
        $tablesObj->setVar('table_notifications', in_array('notifications', $tableOption));
        $tablesObj->setVar('table_permissions', in_array('permissions', $tableOption));
        $tablesObj->setVar('table_rate', in_array('rate', $tableOption));
        $tablesObj->setVar('table_print', in_array('print', $tableOption));
        $tablesObj->setVar('table_pdf', in_array('pdf', $tableOption));
        $tablesObj->setVar('table_rss', in_array('rss', $tableOption));
        $tablesObj->setVar('table_single', in_array('single', $tableOption));
        $tablesObj->setVar('table_visit', in_array('visit', $tableOption));

        if ($tables->insert($tablesObj)) {
            if ($tablesObj->isNew()) {
                $tableTid    = $GLOBALS['xoopsDB']->getInsertId();
                $tableAction = '&field_mid=' . $tableMid . '&field_tid=' . $tableTid . '&field_numb=' . $tableNumbFields . '&field_name=' . $tableFieldname;
                // Fields Elements Handler
                $fieldelementObj = $helper->getHandler('Fieldelements')->create();
                $fieldelementObj->setVar('fieldelement_mid', $tableMid);
                $fieldelementObj->setVar('fieldelement_tid', $tableTid);
                $fieldelementObj->setVar('fieldelement_name', 'Table : ' . ucfirst(\Xmf\Request::getString('table_name', '', 'POST')));
                $fieldelementObj->setVar('fieldelement_value', 'XoopsFormTables-' . ucfirst(\Xmf\Request::getString('table_name', '', 'POST')));
                // Insert new field element id for table name
                if (!$helper->getHandler('Fieldelements')->insert($fieldelementObj)) {
                    $GLOBALS['xoopsTpl']->assign('error', $fieldelementObj->getHtmlErrors() . ' Field element');
                }
                redirect_header('fields.php?op=new' . $tableAction, 5, sprintf(_AM_MODULEBUILDER_TABLE_FORM_CREATED_OK, \Xmf\Request::getString('table_name', '', 'POST')));
            } else {
                redirect_header('tables.php', 5, sprintf(_AM_MODULEBUILDER_TABLE_FORM_UPDATED_OK, \Xmf\Request::getString('table_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        $form = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('tables.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_ADD, 'tables.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_TABLES_LIST, 'tables.php?op=list', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $tablesObj = $helper->getHandler('Tables')->get($tableId);
        $form      = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'order':
        // Initialize tables handler
        $tablesObj = $helper->getHandler('Tables');
        if (isset($_POST['torder'])) {
            $i = 0;
            foreach ($_POST['torder'] as $order) {
                if ($order > 0) {
                    $tableOrder = $tablesObj->get($order);
                    $tableOrder->setVar('table_order', $i);
                    if (!$tablesObj->insert($tableOrder)) {
                        $error = true;
                    }
                    ++$i;
                }
            }
            redirect_header('tables.php', 5, _AM_MODULEBUILDER_TABLE_ORDER_ERROR);
            unset($i);
        }
        exit;
        break;
    case 'delete':
        $tablesObj = $helper->getHandler('Tables')->get($tableId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Tables')->delete($tablesObj)) {
                // Delete items in table fieldelements - idea by goffy
                $fieldelements = $helper->getHandler('Fieldelements')->getAllFieldelementsByModuleAndTableId($tableMid, $tableId);
                foreach (array_keys($fieldelements) as $fe) {
                    $fieldElementsObj = $helper->getHandler('Fieldelements')->get($fieldelements[$fe]->getVar('fieldelement_id'));
                    if (!$helper->getHandler('Fieldelements')->delete($fieldElementsObj)) {
                        $GLOBALS['xoopsTpl']->assign('error', $fieldElementsObj->getHtmlErrors());
                    }
                    unset($fieldElementsObj);
                }
                redirect_header('tables.php', 3, _AM_MODULEBUILDER_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'table_id' => $tableId, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_MODULEBUILDER_FORMSUREDEL, $tablesObj->getVar('table_name')));
        }
        break;
    case 'display':
        $modArray = ['admin', 'user', 'blocks', 'search', 'comments', 'notifications', 'permissions'];
        $mid      = \Xmf\Request::getInt('mod_id', 0, 'POST');
        if ($mid > 0) {
            $modulesObj = $helper->getHandler('Modules')->get($mid);
            foreach ($modArray as $modField) {
                if (isset($_POST['mod_' . $modField])) {
                    $mField = $modulesObj->getVar('mod_' . $modField);
                    $modulesObj->setVar('mod_' . $modField, !$mField);
                }
            }
            if ($helper->getHandler('Modules')->insert($modulesObj)) {
                redirect_header('modules.php', 3, _AM_MODULEBUILDER_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        $tableArray = ['admin', 'user', 'blocks', 'submenu', 'search', 'comments', 'notifications', 'permissions'];
        $tid        = \Xmf\Request::getInt('table_id', 0, 'POST');
        if ($tid > 0) {
            $tablesObj = $helper->getHandler('Tables')->get($tid);
            foreach ($tableArray as $tableField) {
                if (isset($_POST['table_' . $tableField])) {
                    $tblField = $tablesObj->getVar('table_' . $tableField);
                    $tablesObj->setVar('table_' . $tableField, !$tblField);
                }
            }
            if ($helper->getHandler('Tables')->insert($tablesObj)) {
                redirect_header('tables.php', 3, _AM_MODULEBUILDER_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        }
        break;
}
include __DIR__ . '/footer.php';

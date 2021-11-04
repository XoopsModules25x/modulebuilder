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
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */

use Xmf\Request;
use XoopsModules\Modulebuilder\{
    Helper
};

/** @var Helper $helper */

// Define main template
$templateMain = 'modulebuilder_modules.tpl';

require __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op    = Request::getString('op', 'list');
$modId = Request::getInt('mod_id');

/**
 * @param $tableName
 *
 * @return array
 */
function importFields($tableName)
{
    $table = $GLOBALS['xoopsDB']->prefix((string)$tableName);

    $sql    = 'SHOW COLUMNS FROM ' . $table;
    $result = $GLOBALS['xoopsDB']->query($sql);

    $tFields = [];

    while ($data = $GLOBALS['xoopsDB']->fetchBoth($result)) {
        $t          = [];
        $t['Field'] = $data['Field'];
        $t['Type']  = $data['Type'];

        if ('YES' === $data['Null']) {
            $t['Null'] = 'NULL';
        } else {
            $t['Null'] = 'NOT NULL';
        }
        $t['Key']     = $data['Key'];
        $t['Default'] = $data['Default'];
        $t['Extra']   = $data['Extra'];

        $t['Label'] = isset($data['Label']) ? $data['Label'] : '';

        $h = strpos($data['Type'], '(');
        $i = strpos($data['Type'], ')');
        if (false === $h) {
            $t['Len'] = 0;
        } else {
            $t['Type'] = substr($data['Type'], 0, $h);
            if ('double' === $t['Type'] || 'float' === $t['Type'] || 'real' === $t['Type']) {
                $t['Len'] = substr($data['Type'], $h + 1, $i - 1 - $h);
            } else {
                $t['Len'] = (int)substr($data['Type'], $h + 1, $i - 1 - $h);
            }
            if (strlen($data['Type']) > $i) {
                $t['Signed'] = substr($data['Type'], $i + 2);
            }
        }

        $tFields[$t['Field']] = $t;

        return $tFields;
    }
}

function importModule()
{
    $moduleName    = isset($_POST['moduleName']) ? $_POST['moduleName'] : 'newModule';
    $moduleNewName = isset($_POST['moduleNewName']) ? $_POST['moduleNewName'] : $moduleName;
    $moduleDirname = \preg_replace('/[^a-zA-Z0-9]\s+/', '', \mb_strtolower($moduleNewName));

    $helper         = Helper::getInstance();
    $modulesHandler = $helper->getHandler('Modules');
    $newModule      = $modulesHandler->create();

    $newModule->setVar('mod_name', $moduleNewName);
    $newModule->setVar('mod_release', date('Y-m-d'));
    $newModule->setVar('mod_dirname', $moduleDirname);

    $newModule->setVar('mod_version', $helper->getConfig('version')); //$GLOBALS['xoopsModuleConfig']['version']);

    $newModule->setVar('mod_since', $helper->getConfig('since'));
    $newModule->setVar('mod_min_php', $helper->getConfig('min_php'));
    $newModule->setVar('mod_min_xoops', $helper->getConfig('min_xoops'));
    $newModule->setVar('mod_min_admin', $helper->getConfig('min_admin'));
    $newModule->setVar('mod_min_mysql', $helper->getConfig('min_mysql'));

    $newModule->setVar('mod_description', $helper->getConfig('description'));
    $newModule->setVar('mod_author', $helper->getConfig('author'));
    $newModule->setVar('mod_author_mail', $helper->getConfig('author_email'));
    $newModule->setVar('mod_author_website_url', $helper->getConfig('author_website_url'));
    $newModule->setVar('mod_author_website_name', $helper->getConfig('author_website_name'));
    $newModule->setVar('mod_credits', $helper->getConfig('credits'));
    $newModule->setVar('mod_license', $helper->getConfig('license'));
    $newModule->setVar('mod_display_admin', $helper->getConfig('display_admin'));
    $newModule->setVar('mod_display_user', $helper->getConfig('display_user'));
    $newModule->setVar('mod_active_search', $helper->getConfig('active_search'));
    $newModule->setVar('mod_active_comments', $helper->getConfig('active_comments'));
    $newModule->setVar('mod_release_info', $helper->getConfig('release_info'));
    $newModule->setVar('mod_release_file', $helper->getConfig('release_file'));
    $newModule->setVar('mod_manual', $helper->getConfig('manual'));
    $newModule->setVar('mod_manual_file', $helper->getConfig('manual_file'));
    $newModule->setVar('mod_image', 'empty.png');
    $newModule->setVar('mod_demo_site_url', $helper->getConfig('demo_site_url'));
    $newModule->setVar('mod_demo_site_name', $helper->getConfig('demo_site_name'));
    $newModule->setVar('mod_support_url', $helper->getConfig('support_url'));
    $newModule->setVar('mod_support_name', $helper->getConfig('support_name'));
    $newModule->setVar('mod_website_url', $helper->getConfig('website_url'));
    $newModule->setVar('mod_website_name', $helper->getConfig('website_name'));
    $newModule->setVar('mod_status', $helper->getConfig('status'));

    $newModule->setVar('mod_admin', $helper->getConfig('display_admin'));
    $newModule->setVar('mod_user', $helper->getConfig('display_user'));
    $newModule->setVar('mod_search', $helper->getConfig('active_search'));
    $newModule->setVar('mod_comments', $helper->getConfig('active_comments'));
    $newModule->setVar('mod_notifications', $helper->getConfig('active_notifications'));
    $newModule->setVar('mod_permissions', $helper->getConfig('active_permissions'));
    $newModule->setVar('mod_donations', $helper->getConfig('donations'));
    $newModule->setVar('mod_subversion', $helper->getConfig('subversion'));

    if ($modulesHandler->insert($newModule)) {
        // get the ID of the new module
        $criteria     = new \Criteria('mod_name', $moduleNewName);
        $moduleObject = $modulesHandler->getObjects($criteria, false, true);
        $moduleId     = $moduleObject[0]->getVar('mod_id');
        importTables($moduleId, $moduleName);
    }
}

/**
 * @param $moduleId
 * @param $moduleName
 */
function importTables($moduleId, $moduleName)
{
    $helper        = Helper::getInstance();
    $tablesHandler = $helper->getHandler('Tables');
    $fieldsHandler = $helper->getHandler('Fields');

    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleName);
    $moduleTables  = $module->getInfo('tables');

    if (false !== $moduleTables && is_array($moduleTables)) {
        foreach ($moduleTables as $table) {
            //create a new tablesholder
            $newTable = $tablesHandler->create();
            $newTable->setVar('table_mid', $moduleId);

            $newTable->setVar('table_name', $table);
            $newTable->setVar('table_image', 'alert.png');

            //get all the fields for this table
            $importedFields = importFields($table);

            //set the number of fields for this table
            $countFields = count($importedFields);
            $newTable->setVar('table_nbfields', $countFields);

            $currentNumber = 0;

            foreach ($importedFields as $t) {
                $fieldsObj = $fieldsHandler->create();
                $fieldsObj->setVar('field_mid', $moduleId);
                $fieldsObj->setVar('field_tid', $newTable->getVar('table_id'));
                $fieldsObj->setVar('field_order', $currentNumber);
                $fieldsObj->setVar('field_name', $t['Field']);
                $fieldsObj->setVar('field_type', $t['Type']);
                $fieldsObj->setVar('field_value', $t['Len']);
                $fieldsObj->setVar('field_attribute', $t['Signed'] ?? '');
                $fieldsObj->setVar('field_null', $t['Null'] ?? '');
                $fieldsObj->setVar('field_default', $t['Default']);

                if (isset($t['Key'])) {
                    if ('PRI' === $t['Key']) {
                        $fieldsObj->setVar('field_key', 'primary');
                    } else {
                        if ('UNI' === $t['Key']) {
                            $fieldsObj->setVar('field_key', 'unique');
                        }
                    }
                }
                $fieldsObj->setVar('field_element', $t['Field']);

                if ($currentNumber < $countFields - 1) {
                }

                if (0 == $currentNumber) {
                    if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set',])) {
                        // XoopsFormTextArea
                        $fieldsObj->setVar('field_element', '3');
                    } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar',])) {
                        //XoopsFormText
                        $fieldsObj->setVar('field_element', '2');
                    } elseif ('datetime' === $t['Type']) {
                        //XoopsFormDateTime //XoopsFormDatePicker
                        $fieldsObj->setVar('field_element', '21');
                    } elseif ('date' === $t['Type']) {
                        //XoopsFormTextDateSelect
                        $fieldsObj->setVar('field_element', '15');
                    }
                } else {
                    if ($currentNumber > 0) {
                        if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set',])) {
                            //XoopsFormTextArea
                            $fieldsObj->setVar('field_element', '3');
                        } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar',])) {
                            //XoopsFormText
                            $fieldsObj->setVar('field_element', '2');
                        } elseif ('datetime' === $t['Type']) {
                            //XoopsFormDateTime //XoopsFormDatePicker
                            $fieldsObj->setVar('field_element', '21');
                        } elseif ('date' === $t['Type']) {
                            //XoopsFormTextDateSelect
                            $fieldsObj->setVar('field_element', '15');
                        }
                    }
                }

                ++$currentNumber;

                $fieldsHandler->insert($fieldsObj);
            }

            if ($tablesHandler->insert($newTable)) {
                echo $table . ' Table has been imported <br>';
                //success, redirect
            }
        }
    }
}

switch ($op) {
    case 'list':
    default:
        $start = Request::getInt('start', 0);
        $limit = Request::getInt('limit', $helper->getConfig('modules_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new', 'add');

        $adminObject->addItemButton(_AM_MODULEBUILDER_MODULES_IMPORT, 'import_module.php', 'compfile');

        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        $modulesAll   = $helper->getHandler('Modules')->getAllModules($start, $limit);
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            \redirect_header('modules.php?op=new', 2, \_AM_MODULEBUILDER_THEREARENT_MODULES2);
        }
        // Display modules list
        if ($modulesCount > 0) {
            foreach (\array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValuesModules();
                $GLOBALS['xoopsTpl']->append('modules_list', $module);
                unset($module);
            }
            if ($modulesCount > $limit) {
                require_once \XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', \_AM_MODULEBUILDER_THEREARENT_MODULES);
        }

        break;
    case 'new':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $settings = $helper->getHandler('Settings')->getActiveSetting();
        if (0 == \count($settings)) {
            \redirect_header('settings.php', 5, \_AM_MODULEBUILDER_MODULE_NOACTSET);
        }
        $modulesObj = $helper->getHandler('Modules')->create();
        $form       = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'modules_import':
        importModule();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('modules.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($modId)) {
            $modulesObj = $helper->getHandler('Modules')->get($modId);
        } else {
            $modulesObj = $helper->getHandler('Modules')->create();
        }
        $moduleDirname = \preg_replace('/[^a-zA-Z0-9]\s+/', '', \mb_strtolower(Request::getString('mod_dirname', '', 'POST')));
        //Form module save
        $modulesObj->setVars(
            [
                'mod_name'                => Request::getString('mod_name', '', 'POST'),
                'mod_dirname'             => $moduleDirname,
                'mod_version'             => Request::getString('mod_version', '', 'POST'),
                'mod_since'               => Request::getString('mod_since', '', 'POST'),
                'mod_min_php'             => Request::getString('mod_min_php', '', 'POST'),
                'mod_min_xoops'           => Request::getString('mod_min_xoops', '', 'POST'),
                'mod_min_admin'           => Request::getString('mod_min_admin', '', 'POST'),
                'mod_min_mysql'           => Request::getString('mod_min_mysql', '', 'POST'),
                'mod_description'         => Request::getString('mod_description', '', 'POST'),
                'mod_author'              => Request::getString('mod_author', '', 'POST'),
                'mod_author_mail'         => Request::getString('mod_author_mail', '', 'POST'),
                'mod_author_website_url'  => Request::getString('mod_author_website_url', '', 'POST'),
                'mod_author_website_name' => Request::getString('mod_author_website_name', '', 'POST'),
                'mod_credits'             => Request::getString('mod_credits', '', 'POST'),
                'mod_license'             => Request::getString('mod_license', '', 'POST'),
                'mod_release_info'        => Request::getString('mod_release_info', '', 'POST'),
                'mod_release_file'        => Request::getString('mod_release_file', '', 'POST'),
                'mod_manual'              => Request::getString('mod_manual', '', 'POST'),
                'mod_manual_file'         => Request::getString('mod_manual_file', '', 'POST'),
            ]
        );
        //Form mod_image
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new \XoopsMediaUploader(
            TDMC_UPLOAD_IMGMOD_PATH, $helper->getConfig('mimetypes_image'), $helper->getConfig('maxsize_image'), null, null
        );
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = &$uploader->getErrors();
                \redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $modulesObj->setVar('mod_image', $uploader->getSavedFileName());
            }
        } else {
            $modulesObj->setVar('mod_image', Request::getString('mod_image', '', 'POST'));
        }
        //Form module save
        $modulesObj->setVars(
            [
                'mod_demo_site_url'  => Request::getString('mod_demo_site_url', '', 'POST'),
                'mod_demo_site_name' => Request::getString('mod_demo_site_name', '', 'POST'),
                'mod_support_url'    => Request::getString('mod_support_url', '', 'POST'),
                'mod_support_name'   => Request::getString('mod_support_name', '', 'POST'),
                'mod_website_url'    => Request::getString('mod_website_url', '', 'POST'),
                'mod_website_name'   => Request::getString('mod_website_name', '', 'POST'),
                'mod_release'        => Request::getString('mod_release', '', 'POST'),
                'mod_status'         => Request::getString('mod_status', '', 'POST'),
                'mod_donations'      => Request::getString('mod_donations', '', 'POST'),
                'mod_subversion'     => Request::getString('mod_subversion', '', 'POST'),
            ]
        );
        $moduleOption = Request::getArray('module_option', []);
        $modulesObj->setVar('mod_admin', \in_array('admin', $moduleOption));
        $modulesObj->setVar('mod_user', \in_array('user', $moduleOption));
        $modulesObj->setVar('mod_blocks', \in_array('blocks', $moduleOption));
        $modulesObj->setVar('mod_search', \in_array('search', $moduleOption));
        $modulesObj->setVar('mod_comments', \in_array('comments', $moduleOption));
        $modulesObj->setVar('mod_notifications', \in_array('notifications', $moduleOption));
        $modulesObj->setVar('mod_permissions', \in_array('permissions', $moduleOption));
        //$modulesObj->setVar('mod_inroot_copy', \in_array('inroot_copy', $moduleOption));

        if ($helper->getHandler('Modules')->insert($modulesObj)) {
            if ($modulesObj->isNew()) {
                \redirect_header('tables.php', 5, \sprintf(\_AM_MODULEBUILDER_MODULE_FORM_CREATED_OK, Request::getString('mod_name', '', 'POST')));
            } else {
                \redirect_header('modules.php', 5, \sprintf(\_AM_MODULEBUILDER_MODULE_FORM_UPDATED_OK, Request::getString('mod_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        $form = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new', 'add');
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $modulesObj = $helper->getHandler('Modules')->get($modId);
        $form       = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $modulesObj = $helper->getHandler('Modules')->get($modId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                \redirect_header('modules.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            //delete all morefiles
            $critDelete = new \CriteriaCompo();
            $critDelete->add(new \Criteria('file_mid', $modId));
            $helper->getHandler('Morefiles')->deleteAll($critDelete);
            unset($critDelete);
            //delete all fields
            $critDelete = new \CriteriaCompo();
            $critDelete->add(new \Criteria('field_mid', $modId));
            $helper->getHandler('Fields')->deleteAll($critDelete);
            unset($critDelete);
            //delete all tables
            $critDelete = new \CriteriaCompo();
            $critDelete->add(new \Criteria('table_mid', $modId));
            $helper->getHandler('Tables')->deleteAll($critDelete);
            unset($critDelete);
            //delete module
            if ($helper->getHandler('Modules')->delete($modulesObj)) {
                \redirect_header('modules.php', 3, \_AM_MODULEBUILDER_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
            }
        } else {
            $xoopsconfirm = new \XoopsModules\Modulebuilder\Common\XoopsConfirm(
                ['ok' => 1, 'mod_id' => $modId, 'op' => 'delete'],
                \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'),
                $modulesObj->getVar('mod_name')
            );
            $form         = $xoopsconfirm->getFormXoopsConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'display':
        $modFieldArray = ['admin', 'user', 'blocks', 'search', 'comments', 'notifications', 'permissions'];
        $id            = Request::getInt('mod_id', 0, 'POST');
        if ($id > 0) {
            $modulesObj = $helper->getHandler('Modules')->get($id);
            foreach ($modFieldArray as $moduleField) {
                if (isset($_POST['mod_' . $moduleField])) {
                    $modField = $modulesObj->getVar('mod_' . $moduleField);
                    $modulesObj->setVar('mod_' . $moduleField, !$modField);
                }
            }
            if ($helper->getHandler('Modules')->insert($modulesObj)) {
                \redirect_header('modules.php', 3, \_AM_MODULEBUILDER_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        break;
    case 'clone':
        $modIdSource = Request::getInt('mod_id', 0);
        if ($modIdSource > 0) {
            //clone data table modules
            $modulesHandler = $helper->getHandler('Modules');
            $tablesHandler  = $helper->getHandler('Tables');
            $fieldsHandler  = $helper->getHandler('Fields');
            $moduleSource   = $modulesHandler->get($modIdSource);
            $moduleTarget   = $modulesHandler->create();
            $sourceVars     = $moduleSource->getVars();
            foreach ($sourceVars as $varKey => $varArray) {
                if ('mod_id' !== $varKey) {
                    if (in_array($varKey, ['mod_name', 'mod_dirname'])) {
                        for ($i = 1; $i <= 10; $i++) {
                            $uniqValue = $varArray['value'] . $i;
                            $result    = $GLOBALS['xoopsDB']->query(
                                'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('modulebuilder_modules') . " as ms WHERE ms.{$varKey} = '{$uniqValue}'"
                            );
                            $num_rows  = $GLOBALS['xoopsDB']->getRowsNum($result);
                            if ($num_rows == 0) {
                                break;
                            }
                        }
                        $moduleTarget->setVar($varKey, $uniqValue);
                    } else {
                        $moduleTarget->setVar($varKey, $varArray['value']);
                    }
                }
            }

            if ($modulesHandler->insert($moduleTarget)) {
                //get new mod_id
                $modIdTarget = $GLOBALS['xoopsDB']->getInsertId();
            } else {
                \redirect_header('modules.php', 5, \_AM_MODULEBUILDER_MODULE_CLONE_ERROR);
            }

            //clone data table tables
            $resultTables = $GLOBALS['xoopsDB']->query(
                'SELECT table_id FROM ' . $GLOBALS['xoopsDB']->prefix('modulebuilder_tables') . " as ts WHERE ts.table_mid = '{$modIdSource}'"
            );
            $num_rows1    = $GLOBALS['xoopsDB']->getRowsNum($resultTables);
            if ($num_rows1 > 0) {
                while (false !== ($myTables = $GLOBALS['xoopsDB']->fetchArray($resultTables))) {
                    $tableIdSource = $myTables['table_id'];
                    $tableSource   = $tablesHandler->get($tableIdSource);
                    $tableTarget   = $tablesHandler->create();
                    $sourceVars    = $tableSource->getVars();
                    foreach ($sourceVars as $varKey => $varArray) {
                        //skip table_id
                        if ('table_id' !== $varKey) {
                            //replace mod_id by new mod_id
                            if ('table_mid' === $varKey) {
                                $tableTarget->setVar($varKey, $modIdTarget);
                            } else {
                                $tableTarget->setVar($varKey, $varArray['value']);
                            }
                        }
                    }
                    if ($tablesHandler->insert($tableTarget)) {
                        //get new table_id
                        $tableIdTarget = $GLOBALS['xoopsDB']->getInsertId();
                    } else {
                        \redirect_header('modules.php', 5, \_AM_MODULEBUILDER_MODULE_CLONE_ERROR);
                    }

                    //clone data table fields
                    $resultFields = $GLOBALS['xoopsDB']->query(
                        'SELECT field_id FROM ' . $GLOBALS['xoopsDB']->prefix('modulebuilder_fields') . " as fs WHERE fs.field_tid = '{$tableIdSource}'"
                    );
                    $num_rows2    = $GLOBALS['xoopsDB']->getRowsNum($resultFields);
                    if ($num_rows2 > 0) {
                        while (false !== ($myField = $GLOBALS['xoopsDB']->fetchArray($resultFields))) {
                            $fieldIdSource = $myField['field_id'];
                            $fieldsSource  = $fieldsHandler->get($fieldIdSource);
                            $fieldsTarget  = $fieldsHandler->create();
                            $sourceVars    = $fieldsSource->getVars();
                            foreach ($sourceVars as $varKey => $varArray) {
                                //skip field_id
                                if ('field_id' !== $varKey) {
                                    if ('field_mid' === $varKey) {
                                        //replace mod_id by new mod_id
                                        $fieldsTarget->setVar($varKey, $modIdTarget);
                                    } elseif ('field_tid' === $varKey) {
                                        //replace table_id by new table_id
                                        $fieldsTarget->setVar($varKey, $tableIdTarget);
                                    } else {
                                        $fieldsTarget->setVar($varKey, $varArray['value']);
                                    }
                                }
                            }
                            if (!$fieldsHandler->insert($fieldsTarget)) {
                                \redirect_header('modules.php', 5, \_AM_MODULEBUILDER_MODULE_CLONE_ERROR);
                            }
                        }
                    }
                }
            }

            \redirect_header('modules.php', 5, \_AM_MODULEBUILDER_MODULE_CLONE_SUCCESS);
        }

        break;
}

require __DIR__ . '/footer.php';

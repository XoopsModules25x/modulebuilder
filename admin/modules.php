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
    Helper,
    Import
};

/** @var Helper $helper */

// Define main template
$templateMain = 'modulebuilder_modules.tpl';

require __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op    = Request::getString('op', 'list');
$modId = Request::getInt('mod_id');

switch ($op) {
    case 'list':
    default:
        $start = Request::getInt('start');
        $limit = Request::getInt('limit', $helper->getConfig('modules_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new');

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
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
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
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $result = Import::importModule();
        if (false === $result['result']) {
            $GLOBALS['xoopsTpl']->assign('error', $result['error']);
        } else {
            $GLOBALS['xoopsTpl']->assign('tables_list', $result['tables']);
        }
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
        $adminObject->addItemButton(\_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new');
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
            $xoopsconfirm = new \XoopsModules\Modulebuilder\Common\Confirm(
                ['ok' => 1, 'mod_id' => $modId, 'op' => 'delete'],
                \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'),
                $modulesObj->getVar('mod_name')
            );
            $form         = $xoopsconfirm->getFormConfirm();
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
        $modIdSource = Request::getInt('mod_id');
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
                        $uniqValue = '';
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
            if (!$resultTables instanceof \mysqli_result) {
                \trigger_error($GLOBALS['xoopsDB']->error());
            }
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
                    if (!$resultFields instanceof \mysqli_result) {
                        \trigger_error($GLOBALS['xoopsDB']->error());
                    }
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

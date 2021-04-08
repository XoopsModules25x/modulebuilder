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

// Define main template
$templateMain = 'modulebuilder_modules.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op    = \Xmf\Request::getString('op', 'list');
$modId = \Xmf\Request::getInt('mod_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('modules_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        $modulesAll   = $helper->getHandler('Modules')->getAllModules($start, $limit);
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            \redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_THEREARENT_MODULES2);
        }
        // Display modules list
        if ($modulesCount > 0) {
            foreach (\array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValuesModules();
                $GLOBALS['xoopsTpl']->append('modules_list', $module);
                unset($module);
            }
            if ($modulesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_MODULES);
        }

        break;
    case 'new':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $settings = $helper->getHandler('Settings')->getActiveSetting();
        if (0 == \count($settings)) {
            \redirect_header('settings.php', 5, _AM_MODULEBUILDER_MODULE_NOACTSET);
        }
        $modulesObj = $helper->getHandler('Modules')->create();
        $form       = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
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
        $moduleDirname = \preg_replace('/[^a-zA-Z0-9]\s+/', '', \mb_strtolower(\Xmf\Request::getString('mod_dirname', '', 'POST')));
        //Form module save
        $modulesObj->setVars(
            [
                'mod_name'                => \Xmf\Request::getString('mod_name', '', 'POST'),
                'mod_dirname'             => $moduleDirname,
                'mod_version'             => \Xmf\Request::getString('mod_version', '', 'POST'),
                'mod_since'               => \Xmf\Request::getString('mod_since', '', 'POST'),
                'mod_min_php'             => \Xmf\Request::getString('mod_min_php', '', 'POST'),
                'mod_min_xoops'           => \Xmf\Request::getString('mod_min_xoops', '', 'POST'),
                'mod_min_admin'           => \Xmf\Request::getString('mod_min_admin', '', 'POST'),
                'mod_min_mysql'           => \Xmf\Request::getString('mod_min_mysql', '', 'POST'),
                'mod_description'         => \Xmf\Request::getString('mod_description', '', 'POST'),
                'mod_author'              => \Xmf\Request::getString('mod_author', '', 'POST'),
                'mod_author_mail'         => \Xmf\Request::getString('mod_author_mail', '', 'POST'),
                'mod_author_website_url'  => \Xmf\Request::getString('mod_author_website_url', '', 'POST'),
                'mod_author_website_name' => \Xmf\Request::getString('mod_author_website_name', '', 'POST'),
                'mod_credits'             => \Xmf\Request::getString('mod_credits', '', 'POST'),
                'mod_license'             => \Xmf\Request::getString('mod_license', '', 'POST'),
                'mod_release_info'        => \Xmf\Request::getString('mod_release_info', '', 'POST'),
                'mod_release_file'        => \Xmf\Request::getString('mod_release_file', '', 'POST'),
                'mod_manual'              => \Xmf\Request::getString('mod_manual', '', 'POST'),
                'mod_manual_file'         => \Xmf\Request::getString('mod_manual_file', '', 'POST'),
            ]
        );
        //Form mod_image
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
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
            $modulesObj->setVar('mod_image', \Xmf\Request::getString('mod_image', '', 'POST'));
        }
        //Form module save
        $modulesObj->setVars(
            [
                'mod_demo_site_url'  => \Xmf\Request::getString('mod_demo_site_url', '', 'POST'),
                'mod_demo_site_name' => \Xmf\Request::getString('mod_demo_site_name', '', 'POST'),
                'mod_support_url'    => \Xmf\Request::getString('mod_support_url', '', 'POST'),
                'mod_support_name'   => \Xmf\Request::getString('mod_support_name', '', 'POST'),
                'mod_website_url'    => \Xmf\Request::getString('mod_website_url', '', 'POST'),
                'mod_website_name'   => \Xmf\Request::getString('mod_website_name', '', 'POST'),
                'mod_release'        => \Xmf\Request::getString('mod_release', '', 'POST'),
                'mod_status'         => \Xmf\Request::getString('mod_status', '', 'POST'),
                'mod_donations'      => \Xmf\Request::getString('mod_donations', '', 'POST'),
                'mod_subversion'     => \Xmf\Request::getString('mod_subversion', '', 'POST'),
            ]
        );
        $moduleOption = \Xmf\Request::getArray('module_option', []);
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
                \redirect_header('tables.php', 5, \sprintf(_AM_MODULEBUILDER_MODULE_FORM_CREATED_OK, \Xmf\Request::getString('mod_name', '', 'POST')));
            } else {
                \redirect_header('modules.php', 5, \sprintf(_AM_MODULEBUILDER_MODULE_FORM_UPDATED_OK, \Xmf\Request::getString('mod_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        $form = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MODULES_ADD, 'modules.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_MODULES_LIST, 'modules.php', 'list');
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
                \redirect_header('modules.php', 3, _AM_MODULEBUILDER_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
            }
        } else {
            $xoopsconfirm = new \XoopsModules\Modulebuilder\Common\XoopsConfirm(
                ['ok' => 1, 'mod_id' => $modId, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), $modulesObj->getVar('mod_name')
            );
            $form         = $xoopsconfirm->getFormXoopsConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'display':
        $modFieldArray = ['admin', 'user', 'blocks', 'search', 'comments', 'notifications', 'permissions'];
        $id            = \Xmf\Request::getInt('mod_id', 0, 'POST');
        if ($id > 0) {
            $modulesObj = $helper->getHandler('Modules')->get($id);
            foreach ($modFieldArray as $moduleField) {
                if (isset($_POST['mod_' . $moduleField])) {
                    $modField = $modulesObj->getVar('mod_' . $moduleField);
                    $modulesObj->setVar('mod_' . $moduleField, !$modField);
                }
            }
            if ($helper->getHandler('Modules')->insert($modulesObj)) {
                \redirect_header('modules.php', 3, _AM_MODULEBUILDER_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        break;
}

include __DIR__ . '/footer.php';


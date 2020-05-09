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
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */
$GLOBALS['xoopsOption']['template_main'] = 'modulebuilder_addfiles.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = \Xmf\Request::getString('op', 'list');

$fileId = \Xmf\Request::getInt('file_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('addfiles_adminpager'));
        // Define main template
        //        $templateMain = 'modulebuilder_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addfiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_FILE, 'addfiles.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgfile_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_NOT_MODULES);
        }
        $addfilesCount = $helper->getHandler('Addfiles')->getCountAddFiles();
        $addfilesAll   = $helper->getHandler('Addfiles')->getAllAddFiles($start, $limit);
        // Display addfiles list
        if ($addfilesCount > 0) {
            foreach (array_keys($addfilesAll) as $i) {
                $files = $addfilesAll[$i]->getAddFilesValues();
                $GLOBALS['xoopsTpl']->append('files_list', $files);
                unset($files);
            }
            if ($addfilesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($addfilesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_MODULES);
        }
        break;
    case 'new':
        // Define main template
        //        $templateMain = 'modulebuilder_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addfiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADDFILES_LIST, 'addfiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $addfilesObj = $helper->getHandler('Addfiles')->create();
        $form        = $addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('addfiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($fileId)) {
            $addfilesObj = $helper->getHandler('Addfiles')->get($fileId);
        } else {
            $addfilesObj = $helper->getHandler('Addfiles')->create();
        }
        // Form file save
        $addfilesObj->setVars(
            [
                'file_mid'       => \Xmf\Request::getString('file_mid', '', 'POST'),
                'file_name'      => \Xmf\Request::getString('file_name', '', 'POST'),
                'file_extension' => \Xmf\Request::getString('file_extension', '', 'POST'),
                'file_infolder'  => \Xmf\Request::getString('file_infolder', '', 'POST'),
            ]
        );

        if ($helper->getHandler('Addfiles')->insert($addfilesObj)) {
            if ($addfilesObj->isNew()) {
                redirect_header('addfiles.php', 5, sprintf(_AM_MODULEBUILDER_FILE_FORM_CREATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            } else {
                redirect_header('addfiles.php', 5, sprintf(_AM_MODULEBUILDER_FILE_FORM_UPDATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $addfilesObj->getHtmlErrors());
        $form = $addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Define main template
        //        $templateMain = 'modulebuilder_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('addfiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_MODULE, 'addfiles.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADDFILES_LIST, 'addfiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $addfilesObj = $helper->getHandler('Addfiles')->get($fileId);
        $form        = $addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $addfilesObj = $helper->getHandler('Addfiles')->get($fileId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('addfiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Addfiles')->delete($addfilesObj)) {
                redirect_header('addfiles.php', 3, _AM_MODULEBUILDER_FORM_DELETED_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $addfilesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'file_id' => $fileId, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_MODULEBUILDER_FORM_SURE_DELETE, $addfilesObj->getVar('file_name')));
        }
        break;
}

include __DIR__ . '/footer.php';

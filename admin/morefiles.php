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

// Define main template
$templateMain = 'modulebuilder_morefiles.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = \Xmf\Request::getString('op', 'list');

$fileId = \Xmf\Request::getInt('file_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('morefiles_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('morefiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MORE_FILES_ADD, 'morefiles.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgfile_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_THEREARENT_MODULES2);
        }
        $morefilesCount = $helper->getHandler('Morefiles')->getCountMorefiles();
        $morefilesAll   = $helper->getHandler('Morefiles')->getAllMorefiles($start, $limit);
        // Display morefiles list
        if ($morefilesCount > 0) {
            foreach (array_keys($morefilesAll) as $i) {
                $files = $morefilesAll[$i]->getValuesMorefiles();
                $GLOBALS['xoopsTpl']->append('files_list', $files);
                unset($files);
            }
            if ($morefilesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($morefilesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_MORE_FILES);
        }
        break;
    case 'new':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('morefiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MORE_FILES_LIST, 'morefiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $morefilesObj = $helper->getHandler('Morefiles')->create();
        $form         = $morefilesObj->getFormMorefiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('morefiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($fileId)) {
            $morefilesObj = $helper->getHandler('Morefiles')->get($fileId);
        } else {
            $morefilesObj = $helper->getHandler('Morefiles')->create();
        }
        // Form file save
        $morefilesObj->setVars(
            [
                'file_mid'       => \Xmf\Request::getInt('file_mid', 0, 'POST'),
                'file_type'      => \Xmf\Request::getString('file_type', '', 'POST'),
                'file_name'      => \Xmf\Request::getString('file_name', '', 'POST'),
                'file_extension' => \Xmf\Request::getString('file_extension', '', 'POST'),
                'file_upload'    => \Xmf\Request::getString('file_upload', '', 'POST'),
                'file_infolder'  => \Xmf\Request::getString('file_infolder', '', 'POST'),
            ]
        );

        if ($helper->getHandler('Morefiles')->insert($morefilesObj)) {
            if ($morefilesObj->isNew()) {
                redirect_header('morefiles.php', 5, sprintf(_AM_MODULEBUILDER_FILE_FORM_CREATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            } else {
                redirect_header('morefiles.php', 5, sprintf(_AM_MODULEBUILDER_FILE_FORM_UPDATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $morefilesObj->getHtmlErrors());
        $form = $morefilesObj->getFormMorefiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('morefiles.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_MORE_FILES_ADD, 'morefiles.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_MORE_FILES_LIST, 'morefiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $morefilesObj = $helper->getHandler('Morefiles')->get($fileId);
        $form         = $morefilesObj->getFormMorefiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $morefilesObj = $helper->getHandler('Morefiles')->get($fileId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('morefiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Morefiles')->delete($morefilesObj)) {
                redirect_header('morefiles.php', 3, _AM_MODULEBUILDER_FORM_DELETED_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $morefilesObj->getHtmlErrors());
            }
        } else {
            $xoopsconfirm = new \XoopsModules\Modulebuilder\Common\XoopsConfirm(
                                        ['ok' => 1, 'file_id' => $fileId, 'op' => 'delete'],
                                        \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'),
                                        $morefilesObj->getVar('file_name')
                            );
            $form = $xoopsconfirm->getFormXoopsConfirm();
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
}

include __DIR__ . '/footer.php';

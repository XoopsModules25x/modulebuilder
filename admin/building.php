<?php

use XoopsModules\Modulebuilder;

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

$templateMain = 'modulebuilder_building.tpl';

include __DIR__ . '/header.php';
$op          = \Xmf\Request::getString('op', 'default');
$mid         = \Xmf\Request::getInt('mod_id');
$inroot_copy = \Xmf\Request::getInt('inroot_copy');
$moduleObj   = $helper->getHandler('Modules')->get($mid);
$cachePath   = XOOPS_VAR_PATH . '/caches/modulebuilder_cache';
if (!is_dir($cachePath)) {
    if (!mkdir($cachePath, 0777) && !is_dir($cachePath)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $cachePath));
    }
    chmod($cachePath, 0777);
}
// Clear cache
if (file_exists($cache = $cachePath . '/classpaths.cache')) {
    unlink($cache);
}
if (!file_exists($indexFile = $cachePath . '/index.html')) {
    copy('index.html', $indexFile);
}
// Switch option
switch ($op) {
    case 'build':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
        // Get var module dirname
        $moduleDirname = $moduleObj->getVar('mod_dirname');
        // Directories for copy from to
        $fromDir = TDMC_UPLOAD_REPOSITORY_PATH . '/' . mb_strtolower($moduleDirname);
        $toDir   = XOOPS_ROOT_PATH . '/modules/' . mb_strtolower($moduleDirname);
        // include_once TDMC_CLASS_PATH . '/building.php';
        if (isset($moduleDirname)) {
            // Clear this module if it's in repository
            $building = Modulebuilder\Building::getInstance();
            if (is_dir($fromDir)) {
                $building->clearDir($fromDir);
            }
        }
        // Structure
        // include_once TDMC_CLASS_PATH . '/files/Architecture.php';
        $handler = Modulebuilder\Files\CreateArchitecture::getInstance();
        // Creation of the structure of folders and files
        $baseArchitecture = $handler->setBaseFoldersFiles($moduleObj);
        if (false !== $baseArchitecture) {
            $GLOBALS['xoopsTpl']->assign('base_architecture', true);
        } else {
            $GLOBALS['xoopsTpl']->assign('base_architecture', false);
        }
        // Get files
        $build = [];
        $files = $handler->setFilesToBuilding($moduleObj);
        foreach ($files as $file) {
            if ($file) {
                $build['list'] = $file;
            }
            $GLOBALS['xoopsTpl']->append('builds', $build);
        }
        unset($build);

        // Get common files
        $resCommon = $handler->setCommonFiles($moduleObj);
        $build['list'] = _AM_MODULEBUILDER_BUILDING_COMMON;
        $GLOBALS['xoopsTpl']->append('builds', $build);
        unset($build);


        // Directory to saved all files
		$building_directory = sprintf(_AM_MODULEBUILDER_BUILDING_DIRECTORY, $moduleDirname);
        
        // Copy this module in root modules
        if (1 === $inroot_copy) {
            $building = Modulebuilder\Building::getInstance();
            if (isset($moduleDirname)) {
                // Clear this module if it's in root/modules
                // Warning: If you have an older operating module with the same name,
                // it's good to make a copy in another safe folder,
                // otherwise it will be deleted irreversibly.
                if (is_dir($fromDir)) {
                    $building->clearDir($toDir);
                }
            }
            $building->copyDir($fromDir, $toDir);
			$building_directory .= sprintf(_AM_MODULEBUILDER_BUILDING_DIRECTORY_INROOT, $toDir);
        }
		$GLOBALS['xoopsTpl']->assign('building_directory', $building_directory);
        break;
    case 'default':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
        // Redirect if there aren't modules
        $nbModules = $helper->getHandler('Modules')->getCount();
        if (0 == $nbModules) {
            redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_NOTMODULES);
        }
        unset($nbModules);
        // include_once TDMC_CLASS_PATH . '/building.php';
        $building = Modulebuilder\Building::getInstance();
        $form     = $building->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
}
include __DIR__ . '/footer.php';

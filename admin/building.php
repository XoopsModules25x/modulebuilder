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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;
use Xmf\Request;

$templateMain = 'modulebuilder_building.tpl';

include __DIR__ . '/header.php';
$op              = Request::getString('op', 'default');
$mid             = Request::getInt('mod_id');
$inrootCopy      = Request::getInt('inroot_copy');
$testdataRestore = Request::getInt('testdata_restore');
$checkData       = Request::hasVar('check_data');
$moduleObj       = $helper->getHandler('Modules')->get($mid);

$cachePath = XOOPS_VAR_PATH . '/caches/modulebuilder_cache_';
if (!\is_dir($cachePath)) {
    if (!\mkdir($cachePath, 0777) && !\is_dir($cachePath)) {
        throw new \RuntimeException(\sprintf('Directory "%s" was not created', $cachePath));
    }
    chmod($cachePath, 0777);
}
// Clear cache
if (\file_exists($cache = $cachePath . '/classpaths.cache')) {
    \unlink($cache);
}
if (!\file_exists($indexFile = $cachePath . '/index.html')) {
    \copy('index.html', $indexFile);
}

if ($checkData > 0) {
    $op = 'check_data';
}
// Switch option
switch ($op) {
    case 'check_data':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        $checkdata = Modulebuilder\Files\CheckData::getInstance();

        // check data for inconsistences
        $checkResults = [];
        $checkResults = $checkdata->getCheckPreBuilding($moduleObj);

        if (\count($checkResults) > 0) {
            //$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
            $GLOBALS['xoopsTpl']->assign('checkResults', $checkResults);
        } else {
            $GLOBALS['xoopsTpl']->assign('checkResultsNice', true);
        }

        break;
    case 'build':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
        $building     = Modulebuilder\Building::getInstance();
        $structure    = Modulebuilder\Files\CreateStructure::getInstance();
        $architecture = Modulebuilder\Files\CreateArchitecture::getInstance();
        $checkdata    = Modulebuilder\Files\CheckData::getInstance();
        // Get var module dirname
        $moduleDirname = $moduleObj->getVar('mod_dirname');

        //save test data of selected module before building new version
        if (1 === $testdataRestore) {
            // Directories for copy from
            $fromDir = XOOPS_ROOT_PATH . '/modules/' . \mb_strtolower($moduleDirname) . '/testdata';
            if (\is_dir($fromDir)) {
                // Directories for copy to
                $toDir = TDMC_UPLOAD_TEMP_PATH . '/' . \mb_strtolower($moduleDirname);
                $structure->isDir($toDir);
                $toDir .= '/testdata';
                if (\is_dir($toDir)) {
                    $building->clearDir($toDir);
                } else {
                    $structure->isDir($toDir);
                }
                $building->copyDir($fromDir, $toDir);
            } else {
                $testdataRestore = 0;
            }
        }

        // Directories for copy from to
        $fromDir = TDMC_UPLOAD_REPOSITORY_PATH . '/' . \mb_strtolower($moduleDirname);
        $toDir   = XOOPS_ROOT_PATH . '/modules/' . \mb_strtolower($moduleDirname);
        // include_once TDMC_CLASS_PATH . '/building.php';
        if (isset($moduleDirname)) {
            // Clear this module if it's in repository
            $building = Modulebuilder\Building::getInstance();
            if (\is_dir($fromDir)) {
                $building->clearDir($fromDir);
            }
        }
        // Structure
        // Creation of the structure of folders and files
        $baseArchitecture = $architecture->setBaseFoldersFiles($moduleObj);
        if (false !== $baseArchitecture) {
            $GLOBALS['xoopsTpl']->assign('base_architecture', true);
        } else {
            $GLOBALS['xoopsTpl']->assign('base_architecture', false);
        }
        // Get files
        $build = [];
        $files = $architecture->setFilesToBuilding($moduleObj);
        foreach ($files as $file) {
            if ($file) {
                $build['list'] = $file;
            }
            $GLOBALS['xoopsTpl']->append('builds', $build);
        }
        unset($build);

        // Get common files
        $resCommon     = $architecture->setCommonFiles($moduleObj);
        $build['list'] = _AM_MODULEBUILDER_BUILDING_COMMON;
        $GLOBALS['xoopsTpl']->append('builds', $build);
        unset($build);

        // Directory to saved all files
        $building_directory = \sprintf(_AM_MODULEBUILDER_BUILDING_DIRECTORY, $moduleDirname);

        // Copy this module in root modules
        if (1 === $inrootCopy) {
            if (isset($moduleDirname)) {
                // Clear this module if it's in root/modules
                // Warning: If you have an older operating module with the same name,
                // it's good to make a copy in another safe folder,
                // otherwise it will be deleted irreversibly.
                if (\is_dir($fromDir)) {
                    $building->clearDir($toDir);
                }
            }
            $building->copyDir($fromDir, $toDir);
            $building_directory .= \sprintf(_AM_MODULEBUILDER_BUILDING_DIRECTORY_INROOT, $toDir);
        }
        if (1 === $testdataRestore) {
            // Directories for copy from to
            $fromDir = TDMC_UPLOAD_TEMP_PATH . '/' . \mb_strtolower($moduleDirname) . '/testdata';
            $toDir   = XOOPS_ROOT_PATH . '/modules/' . \mb_strtolower($moduleDirname) . '/testdata';
            if (\is_dir($toDir)) {
                $building->clearDir($toDir);
            }
            if (\is_dir($fromDir)) {
                $building->copyDir($fromDir, $toDir);
            }
        }

        $GLOBALS['xoopsTpl']->assign('building_directory', $building_directory);
        break;
    case 'default':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('building.php'));
        // Redirect if there aren't modules
        $nbModules = $helper->getHandler('Modules')->getCount();
        if (0 == $nbModules) {
            \redirect_header('modules.php?op=new', 2, _AM_MODULEBUILDER_THEREARENT_MODULES2);
        }
        unset($nbModules);
        // include_once TDMC_CLASS_PATH . '/building.php';
        $building = Modulebuilder\Building::getInstance();
        $form     = $building->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());

        break;
}
include __DIR__ . '/footer.php';

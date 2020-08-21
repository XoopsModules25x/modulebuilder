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

use Xmf\Request;
use XoopsModules\Modulebuilder\Devtools;

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

// Define main template
$templateMain = $moduleDirName . '_devtools.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op    = Request::getString('op', 'list');

switch ($op) {
    case 'fq':
        $fqModule = Request::getString('fq_module');
        $src_path = XOOPS_ROOT_PATH . '/modules/' . $fqModule;
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/fq/' . $fqModule;

        $patKeys = [];
        $patValues = [];
        //Devtools::cloneFileFolder($src_path, $dst_path, $patKeys, $patValues);
        Devtools::function_qualifier($src_path, $dst_path, $fqModule);
        \redirect_header('devtools.php', 3, _AM_MODULEBUILDER_DEVTOOLS_FQ_SUCCESS);
        break;
    case 'check_lang':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('devtools.php'));
        $clModuleName = Request::getString('cl_module');
        $clModuleNameUpper = \mb_strtoupper($clModuleName);

        //scan language files
        $src_path = XOOPS_ROOT_PATH . '/modules/' . $clModuleName . '/language/english/';
        $langfiles = [];
        foreach (scandir($src_path) as $scan) {
            if (is_file($src_path . $scan) && 'index.html' !== $scan && 'common.php' !== $scan) {
                $langfiles[] = $src_path . $scan;
            }
        }
        $moduleConstants = [];
        if (file_exists($src_path . 'common.php')) {
            // include common.php first
            $constantsBeforeInclude = getUserDefinedConstants();
            include_once($src_path . 'common.php');
            $constantsAfterInclude = getUserDefinedConstants();
            $moduleConstants[$src_path . 'common.php'] = array_diff_assoc($constantsAfterInclude, $constantsBeforeInclude);
        }
        foreach ($langfiles as $langfile) {
            $constantsBeforeInclude = getUserDefinedConstants();
            include_once($langfile);
            $constantsAfterInclude = getUserDefinedConstants();
            $moduleConstants[$langfile] = array_diff_assoc($constantsAfterInclude, $constantsBeforeInclude);
        }

        //get all php and tpl files from module
        $check_path = XOOPS_ROOT_PATH . '/modules/' . $clModuleName;
        $Directory = new RecursiveDirectoryIterator($check_path);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $regexFiles = new RegexIterator($Iterator, '/^.+\.(php|tpl)$/i', RecursiveRegexIterator::GET_MATCH);
        //$files = new RegexIterator($flattened, '#^(?:[A-Z]:)?(?:/(?!\.Trash)[^/]+)+/[^/]+\.(?:php|html)$#Di');
        $modfiles = [];
        foreach($regexFiles as $regexFiles) {
            $file = str_replace('\\', '/', $regexFiles[0]);
            if (!\in_array($file, $langfiles)) {
                $modfiles[] = $file;
            }
        }

        //check all constants in all files
        $resultsList = [];
        foreach ($moduleConstants as $keyFile => $constFile) { 
            $resultCheck = [];
            foreach ($constFile as $constKey => $constValue) {
                $found = 0;
                $first = '';
                //search for complete string
                foreach($modfiles as $modfile) {
                    if( strpos(file_get_contents($modfile),$constKey) !== false) {
                        $found = 1;
                        $first = $modfile;
                        break;
                    }
                }
                if (0 == $found) {
                    //search for concatenated string
                    $needle = str_replace('_' . $clModuleNameUpper . '_', "_' . \$moduleDirNameUpper . '_' . '", $constKey);
                    foreach($modfiles as $modfile) {
                        if( strpos(file_get_contents($modfile),$needle) !== false) {
                            $found = 1;
                            $first = $modfile;
                            break;
                        }
                    }
                }
                $resultCheck[] = ['define' => $constKey, 'found' => $found, 'first' => $first];
            }
            $resultsList[] = ['file' => $keyFile, 'result' => $resultCheck];
        }

        $GLOBALS['xoopsTpl']->assign('clresults', $resultsList);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);

        break;
    case 'list':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('devtools.php'));
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/fq/';
        $GLOBALS['xoopsTpl']->assign('fq_desc',\str_replace('%s', $dst_path, _AM_MODULEBUILDER_DEVTOOLS_FQ_DESC));
        $fq_form = Devtools::getFormModulesFq();
        $GLOBALS['xoopsTpl']->assign('fq_form', $fq_form->render());
        $cl_form = Devtools::getFormModulesCl();
        $GLOBALS['xoopsTpl']->assign('cl_form', $cl_form->render());
        $GLOBALS['xoopsTpl']->assign('devtools_list',true);

        break;
}

function getUserDefinedConstants() {
    $constants = get_defined_constants(true);
    return (isset($constants['user']) ? $constants['user'] : array());
}

include __DIR__ . '/footer.php';





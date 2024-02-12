<?php declare(strict_types=1);

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
 * @author          Goffy https://myxoops.org
 */

use Xmf\Request;
use XoopsModules\Modulebuilder\Devtools;

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

// Define main template
$templateMain = $moduleDirName . '_devtools.tpl';

require __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = Request::getString('op', 'list');

switch ($op) {
    case 'fq':
        $modName = Request::getString('fq_module');
        if ('' === $modName) {
            \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
        }
        $src_path = \XOOPS_ROOT_PATH . '/modules/' . $modName;
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/fq/' . $modName;

        $patKeys   = [];
        $patValues = [];
        Devtools::function_qualifier($src_path, $dst_path, $modName);
        \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_FQ_SUCCESS);
        break;
    case 'check_lang':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('devtools.php'));
        $modName      = Request::getString('cl_module');
        if ('' === $modName) {
            \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
        }
        $modNameUpper = \mb_strtoupper($modName);

        //scan language files
        $src_path  = \XOOPS_ROOT_PATH . '/modules/' . $modName . '/language/english/';
        $langfiles = [];
        foreach (scandir($src_path) as $scan) {
            if (is_file($src_path . $scan) && 'index.html' !== $scan) {
                $langfiles[] = $src_path . $scan;
            }
        }
        $moduleConstants = [];
        foreach ($langfiles as $langfile) {
            //$constantsBeforeInclude = getUserDefinedConstants();
            require_once($langfile);
            //$constantsAfterInclude = getUserDefinedConstants();
            //$moduleConstants[$langfile] = array_diff_assoc($constantsAfterInclude, $constantsBeforeInclude);
        }
        $constantsAfterInclude = getUserDefinedConstants();
        foreach ($constantsAfterInclude as $constKey => $constValue) {
            if (mb_strpos($constKey, '_' . $modNameUpper . '_') > 0) {
                $moduleConstants[$constKey] = $constKey;
            }
        }

        //get all php and tpl files from module
        $check_path = \XOOPS_ROOT_PATH . '/modules/' . $modName;
        $Directory  = new RecursiveDirectoryIterator($check_path);
        $Iterator   = new RecursiveIteratorIterator($Directory);
        $regexFiles = new RegexIterator($Iterator, '/^.+\.(php|tpl)$/i', RecursiveRegexIterator::GET_MATCH);
        //$files = new RegexIterator($flattened, '#^(?:[A-Z]:)?(?:/(?!\.Trash)[^/]+)+/[^/]+\.(?:php|html)$#Di');
        $modfiles = [];
        foreach ($regexFiles as $regexFiles) {
            $file = str_replace('\\', '/', $regexFiles[0]);
            if (!\in_array($file, $langfiles)) {
                $modfiles[] = $file;
            }
        }

        //check all constants in all files
        $resultCheck = [];
        foreach ($moduleConstants as $constKey) {
            $foundMod  = 0;
            $first     = '';
            $foundLang = 'not defined';
            //search for complete string
            foreach ($modfiles as $modfile) {
                if (mb_strpos(file_get_contents($modfile), $constKey) !== false) {
                    $foundMod = 1;
                    $first    = $modfile;
                    break;
                }
            }
            if (0 == $foundMod) {
                //search for concatenated string
                $needle = str_replace('_' . $modNameUpper . '_', "_' . \$moduleDirNameUpper . '_", $constKey);
                foreach ($modfiles as $modfile) {
                    if (mb_strpos(file_get_contents($modfile), $needle) !== false) {
                        $foundMod = 1;
                        $first    = $modfile;
                        break;
                    }
                }
            }
            if (0 == $foundMod) {
                //search for concatenated string
                $needle = str_replace('_' . $modNameUpper . '_', "_' . \$moduleDirNameUpper . '_' . '", $constKey);
                foreach ($modfiles as $modfile) {
                    if (mb_strpos(file_get_contents($modfile), $needle) !== false) {
                        $foundMod = 1;
                        $first    = $modfile;
                        break;
                    }
                }
            }
            foreach ($langfiles as $langfile) {
                if (mb_strpos(file_get_contents($langfile), $constKey) !== false) {
                    $foundLang = $langfile;
                    break;
                }
            }
            if ('' == $foundLang) {
                //search for concatenated string
                $needle = str_replace('_' . $modNameUpper . '_', "_' . \$moduleDirNameUpper . '_", $constKey);
                foreach ($langfiles as $langfile) {
                    if (mb_strpos(file_get_contents($langfile), $needle) !== false) {
                        $foundLang = $langfile;
                        break;
                    }
                }
            }
            if ('' == $foundLang) {
                //search for concatenated string
                $needle = str_replace('_' . $modNameUpper . '_', "_' . \$moduleDirNameUpper . '_' . '", $constKey);
                foreach ($langfiles as $langfile) {
                    if (mb_strpos(file_get_contents($langfile), $needle) !== false) {
                        $foundLang = $langfile;
                        break;
                    }
                }
            }
            $resultCheck[\basename($foundLang)][] = ['define' => $constKey, 'found' => $foundMod, 'first' => $first];
        }
        $GLOBALS['xoopsTpl']->assign('clresults', $resultCheck);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);

        break;
    case 'tab_replacer':
        $modName = Request::getString('tab_module');
        if ('' === $modName) {
            \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
        }
        $src_path  = \XOOPS_ROOT_PATH . '/modules/' . $modName;
        $dst_path  = TDMC_UPLOAD_PATH . '/devtools/tab/';
        @\mkdir($dst_path);
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/tab/' . $modName;
        @\mkdir($dst_path);

        Devtools::function_tabreplacer($src_path, $dst_path);
        \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_TAB_SUCCESS);
        break;
    case 'remove_prefix':
        $modName = Request::getString('rp_module');
        if ('' === $modName) {
            \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
        }
        $src_path  = \XOOPS_ROOT_PATH . '/modules/' . $modName;
        $dst_path  = TDMC_UPLOAD_PATH . '/devtools/remove_prefix/';
        @\mkdir($dst_path);
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/remove_prefix/' . $modName;
        @\mkdir($dst_path);

        Devtools::function_removeprefix($src_path, $src_path, $modName);
        \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_RP_SUCCESS);
        break;
    case 'list':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('devtools.php'));
        $dst_path = TDMC_UPLOAD_PATH . '/devtools/fq/';
        $GLOBALS['xoopsTpl']->assign('fq_desc', \str_replace('%s', $dst_path, \_AM_MODULEBUILDER_DEVTOOLS_FQ_DESC));
        $fq_form = Devtools::getFormModulesFq();
        $GLOBALS['xoopsTpl']->assign('fq_form', $fq_form->render());

        $cl_form = Devtools::getFormModulesCl();
        $GLOBALS['xoopsTpl']->assign('cl_form', $cl_form->render());

        $dst_path = TDMC_UPLOAD_PATH . '/devtools/tab/';
        $GLOBALS['xoopsTpl']->assign('tab_desc', \str_replace('%s', $dst_path, \_AM_MODULEBUILDER_DEVTOOLS_TAB_DESC));
        $tab_form = Devtools::getFormModulesTab();
        $GLOBALS['xoopsTpl']->assign('tab_form', $tab_form->render());

        $dst_path = TDMC_UPLOAD_PATH . '/devtools/remove_prefix/';
        $GLOBALS['xoopsTpl']->assign('rp_desc', \str_replace('%s', $dst_path, \_AM_MODULEBUILDER_DEVTOOLS_RP_DESC));
        $tab_form = Devtools::getFormModulesRemovePrefix();
        $GLOBALS['xoopsTpl']->assign('rp_form', $tab_form->render());

        $GLOBALS['xoopsTpl']->assign('devtools_list', true);

        break;
}

function getUserDefinedConstants()
{
    $constants = get_defined_constants(true);

    return ($constants['user'] ?? []);
}

require __DIR__ . '/footer.php';

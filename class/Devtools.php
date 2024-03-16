<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  modulebuilder
 *
 * @license      https://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Goffy https://myxoops.org
 * @author       Mamba <mambax7@gmail.com>
 * @since        
 */

use XoopsModules\Modulebuilder;

/**
 * Class Devtools
 */
class Devtools
{

    /* function to remove prefix from table field names */

    /**
     * @param $src_path
     * @param $dst_path
     * @param $moduleDirName
     */
    public static function function_removeprefix($src_path, $dst_path, $moduleDirName): void
    {
        $patKeys = [];
        $patValues = [];

        $helper  = \XoopsModules\Modulebuilder\Helper::getInstance();
        // get module id
        $crModules = new \CriteriaCompo();
        $crModules->add(new \Criteria('mod_dirname', $moduleDirName));
        $modulesAll  = $helper->getHandler('Modules')->getAll($crModules);
        $modId = 0;
        foreach (\array_keys($modulesAll) as $i) {
            $modId = $modulesAll[$i]->getVar('mod_id');
        }
        if (0 === $modId) {
            \redirect_header('devtools.php', 3, \_AM_MODULEBUILDER_DEVTOOLS_INVALID_MOD);
        }
        // get module tables
        $crTables = new \CriteriaCompo();
        $crTables->add(new \Criteria('table_mid', $modId));
        $tablesAll  = $helper->getHandler('Tables')->getAll($crTables);
        $tablesArr  = [];
        foreach (\array_keys($tablesAll) as $j) {
            $tablesArr[$j] = $tablesAll[$j]->getVar('table_name');
        }

        foreach ($tablesArr as $tid => $tablename) {
            $crFields = new \CriteriaCompo();
            $crFields->add(new \Criteria('field_tid', $tid));
            $fieldsAll  = $helper->getHandler('Fields')->getAll($crFields);
            foreach (\array_keys($fieldsAll) as $k) {
                $fieldName   = $fieldsAll[$k]->getVar('field_name');
                $rpFieldName = '';
                if (\mb_strpos($fieldName, '_')) {
                    $str = \mb_strpos($fieldName, '_');
                    if (false !== $str) {
                        $rpFieldName = \mb_substr($fieldName, $str + 1, \mb_strlen($fieldName));
                    }
                }
                // for getVar, setVar, forms, ....
                $patKeys[]   = "'" .  $fieldName . "'";
                $patValues[] = "'" .  $rpFieldName . "'";
                $patKeys[]   = 'showImgSelected(\"imglabel_' .  $fieldName . '\",';
                $patValues[] = 'showImgSelected(\"imglabel_' .  $rpFieldName . '\",';
                $patKeys[]   = '\"' .  $fieldName . '\",';
                $patValues[] = '\"' .  $rpFieldName . '\",';
                $patKeys[]   = "id='imglabel_" .  $fieldName . "' alt=";
                $patValues[] = "id='imglabel_" .  $rpFieldName . "' alt=";
                $patKeys[]   = "sort = '" .  $fieldName . ' ASC,';
                $patValues[] = "sort = '" .  $rpFieldName . ' ASC,';
                $patKeys[]   = 'ASC, ' .  $fieldName . "', \$order";
                $patValues[] = 'ASC, ' .  $rpFieldName . "', \$order";
                //clone feature
                $patKeys[]   = "'" .  $fieldName . "_source'";
                $patValues[] = "'" .  $rpFieldName . "_source'";
                // for tpl files
                if ($rpFieldName === 'id') {
                    $patKeys[]   = 'op=edit&amp;' .  $fieldName . '=';
                    $patValues[] = 'op=edit&amp;' .  $rpFieldName . '=';
                    $patKeys[]   = 'op=show&amp;' .  $fieldName . '=';
                    $patValues[] = 'op=show&amp;' .  $rpFieldName . '=';
                    $patKeys[]   = 'op=delete&amp;' .  $fieldName . '=';
                    $patValues[] = 'op=delete&amp;' .  $rpFieldName . '=';
                    $patKeys[]   = 'op=broken&amp;' .  $fieldName . '=';
                    $patValues[] = 'op=broken&amp;' .  $rpFieldName . '=';
                    $patKeys[]   = 'op=clone&amp;' .  $fieldName . '_source=';
                    $patValues[] = 'op=clone&amp;' .  $rpFieldName . '_source=';
                }
                //<{$article.art_id|default:false}>
                $patKeys[]   = '<{$' .  $tablename . '.' .  $fieldName . '|default:false}>';
                $patValues[] = '<{$' .  $tablename . '.' .  $rpFieldName . '|default:false}>';
                // for sql file
                $patKeys[] = '`' .  $fieldName . '`';
                $patValues[] = '`' .  $rpFieldName . '`';
            }
        }

        $extensions = [];
        $extensions[] = 'php';
        $extensions[] = 'tpl';
        $extensions[] = 'sql';
        self::cloneFileFolder($src_path, $dst_path, $patKeys, $patValues, false, $extensions);
    }

    /* function to add function qualifier to module */

    /**
     * @param $src_path
     * @param $dst_path
     * @param $moduleDirName
     */
    public static function function_qualifier($src_path, $dst_path, $moduleDirName): void
    {
        $functions = [];
        $constants = [];

        //php functions
        $functions[] = [
            'array_diff',
            'array_filter',
            'array_key_exists',
            'array_keys',
            'array_search',
            'array_slice',
            'array_unshift',
            'array_shift',
            'array_values',
            'array_flip',
            'assert',
            'basename',
            'boolval',
            'call_user_func',
            'call_user_func_array',
            'chr',
            'class_exists',
            'closedir',
            'constant',
            'copy',
            'count',
            'curl_close',
            'curl_error',
            'curl_exec',
            'curl_file_create',
            'curl_getinfo',
            'curl_init',
            'curl_setopt',
            'define',
            'defined',
            'dirname',
            'doubleval',
            'explode',
            'extension_loaded',
            'file_exists',
            'file_get_contents',
            'file_put_contents',
            'filemtime',
            'finfo_open',
            'floatval',
            'floor',
            'formatTimestamp',
            'func_get_args',
            'func_num_args',
            'function_exists',
            'get_called_class',
            'get_class',
            'getimagesize',
            'gettype',
            'http_build_query',
            'imagealphablending',
            'imagecopyresampled',
            'imagecreatefromgif',
            'imagecreatefromjpeg',
            'imagecreatefrompng',
            'imagecreatefromstring',
            'imagecreatetruecolor',
            'imagesavealpha',
            'imagedestroy',
            'imagegif',
            'imagejpeg',
            'imagepng',
            'imagerotate',
            'imagesx',
            'imagesy',
            'implode',
            'in_array',
            'ini_get',
            'intval',
            'include',
            'is_array',
            'is_bool',
            'is_callable',
            'is_dir',
            'is_double',
            'is_file',
            'is_float',
            'is_int',
            'is_integer',
            'is_link',
            'is_long',
            'is_null',
            'is_object',
            'is_real',
            'is_resource',
            'is_string',
            'json_decode',
            'json_encode',
            'libxml_get_errors',
            'libxml_clear_errors',
            'mime_content_type',
            'mkdir',
            'mktime',
            'opendir',
            'ord',
            'pathinfo',
            'preg_match',
            'preg_match_all',
            'preg_replace',
            'readdir',
            'readlink',
            'redirect_header',
            'rename',
            'require',
            'rmdir',
            'round',
            'scandir',
            'sprintf',
            'mb_strtoupper',
            'mb_strtolower',
            'mb_strpos',
            'mb_strlen',
            'mb_strrpos',
            'setcookie',
            'simplexml_load_string',
            'str_repeat',
            'str_replace',
            'stream_context_create',
            'strip_tags',
            'strlen',
            'strpos',
            'strtotime',
            'strval',
            'substr',
            'symlink',
            'time',
            'trigger_error',
            'trim',
            'ucfirst',
            'unlink',
            'version_compare',
        ];

        // xoops functions
        $functions[] = [
            'xoops_getHandler',
            'xoops_load',
            'xoops_loadLanguage',
        ];
        // xoops const
        $constants[] = [
            'XOBJ_DTYPE_',
            'XOOPS_URL',
            'XOOPS_ROOT_PATH',
            'XOOPS_GROUP_',
        ];

        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        // module language defines
        $constants[] = [
            '_AM_' . $moduleDirNameUpper .'_',
            '_MI_' . $moduleDirNameUpper .'_',
            '_MB_' . $moduleDirNameUpper .'_',
            '_MA_' . $moduleDirNameUpper .'_',
            $moduleDirNameUpper .'_',
        ];

        // xoops objects
        $xobjects[] = [
            'XoopsThemeForm',
            'XoopsSimpleForm',
            'XoopsTableForm',
            'XoopsFormText', //XoopsFormTextArea, XoopsFormTextDateSelect
            'XoopsFormHidden',
            'XoopsFormButton', //XoopsFormButtonTray
            'XoopsFormEditor',
            'XoopsFormCheckBox',
            'XoopsFormRadio', //XoopsFormRadioYN
            'XoopsFormSelect', //XoopsFormSelectUser
            'XoopsFormColorPicker',
            'XoopsFormElementTray',
            'XoopsFormLabel',
            'XoopsFormFile',
            'XoopsFormPassword',
            'XoopsFormDateTime',
            'XoopsTpl',
            'XoopsPageNav',
            'XoopsUser',
            'XoopsLists',
            'XoopsDatabase',
            'XoopsMediaUploader',
            'XoopsModule',
            'XoopsPreloadItem',
        ];

        // misc corrections
        $misc = [
            'new Criteria('   => 'new \Criteria(',
            'new CriteriaCompo('   => 'new \CriteriaCompo(',
            "\define('\\"   => "\define('",
        ];

        // repair known errors
        $errors = [
            'substr_\count('   => 'substr_count(',
            'micro\time('   => '\microtime(',
            'mk\time('   => 'mktime(',
            'strto\time('   => 'strtotime(',
            'mb_\strlen('   => 'mb_strlen(',
            'mb_\substr('   => 'mb_substr(',
            'x\copy'        => 'xcopy',
            'r\rmdir'       => 'rrmdir',
            'r\copy'        => 'rcopy',
            'r\trim'        => '\rtrim',
            'l\trim'        => '\ltrim',
            '\dirname()'    => 'dirname()',
            'assw\ord'      => 'assword',
            'mb_\strpos'    => 'mb_strpos',
            'image\copy('   => 'imagecopy(',
            '<{if \count('  => '<{if count(',
            'define(\_'     => 'define(_',
            '\strr\chr('    => '\strrchr(',
            'strf\time('    => 'strftime(',
            'filem\time'     => 'filemtime',
            "_AM_\\" . $moduleDirNameUpper .'_' => "_AM_" . $moduleDirNameUpper .'_',
            "_MI_\\" . $moduleDirNameUpper .'_' => "_MI_" . $moduleDirNameUpper .'_',
            "_MB_\\" . $moduleDirNameUpper .'_' => "_MB_" . $moduleDirNameUpper .'_',
            "_MA_\\" . $moduleDirNameUpper .'_' => "_MA_" . $moduleDirNameUpper .'_',
            "CO_\\" . $moduleDirNameUpper .'_' => "CO_" . $moduleDirNameUpper .'_',
            "'\\" . $moduleDirNameUpper .'_' => "'" . $moduleDirNameUpper .'_',
            "'\_AM_\\" . $moduleDirNameUpper .'_' => "'_AM_" . $moduleDirNameUpper .'_',
            "'\_MI_\\" . $moduleDirNameUpper .'_' => "'_MI_" . $moduleDirNameUpper .'_',
            "'\_MB_\\" . $moduleDirNameUpper .'_' => "'_MB_" . $moduleDirNameUpper .'_',
            "'\_MA_\\" . $moduleDirNameUpper .'_' => "'_MA_" . $moduleDirNameUpper .'_',
            'namespace \XoopsModules' => 'namespace XoopsModules',
            'use \Xoops' => 'use Xoops',
            "'\XOOPS_" => "'XOOPS_",
            "prefix = '\XoopsModules\\" => "prefix = 'XoopsModules\\",
            '\XoopsModules25x' => 'XoopsModules25x',
            '@link \XoopsModule' => '@link XoopsModule',
        ];

        $patterns = [];
        foreach ($functions as $function) {
            //reset existing in order to avoid double \\
            foreach ($function as $item) {
                $patterns['\\' . $item . '('] = $item . '(';
            }
            //apply now for all
            foreach ($function as $item) {
                $patterns[$item . '('] = '\\' . $item . '(';
            }
        }
        foreach ($constants as $constant) {
            //reset existing in order to avoid double \\
            foreach ($constant as $item) {
                $patterns['\\' . $item ] = $item;
            }
            //apply now for all
            foreach ($constant as $item) {
                $patterns[$item] = '\\' . $item;
            }
        }
        foreach ($xobjects as $xobject) {
            //reset existing in order to avoid double \\
            foreach ($xobject as $item) {
                $patterns['\\' . $item ] = $item;
            }
            //apply now for all
            foreach ($xobject as $item) {
                $patterns[$item] = '\\' . $item;
            }
        }

        //add misc
        foreach ($misc as $key => $value) {
            $patterns[$key] = $value;
        }
        //add errors
        foreach ($errors as $key => $value) {
            $patterns[$key] = $value;
        }

        $patKeys   = \array_keys($patterns);
        $patValues = \array_values($patterns);
        self::cloneFileFolder($src_path, $dst_path, $patKeys, $patValues);
    }

    /* function to add function qualifier to module */

    /**
     * @param $src_path
     * @param $dst_path
     */
    public static function function_tabreplacer($src_path, $dst_path): void
    {
        $patKeys   = [];
        $patValues = [];
        self::cloneFileFolder($src_path, $dst_path, $patKeys, $patValues, true);
    }

    // recursive cloning script

    /**
     * @param $src_path
     * @param $dst_path
     * @param array $patKeys
     * @param array $patValues
     * @param bool  $replaceTabs
     */
    public static function cloneFileFolder($src_path, $dst_path, $patKeys = [], $patValues = [], $replaceTabs = false, $extensions = ['php']): void
    {
        // open the source directory
        $dir = \opendir($src_path);
        // Make the destination directory if not exist
        @\mkdir($dst_path);
        // Loop through the files in source directory
        while( $file = \readdir($dir) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( \is_dir($src_path . '/' . $file) ) {
                    // Recursively calling custom copy function for sub directory
                    self::cloneFileFolder($src_path . '/' . $file, $dst_path . '/' . $file, $patKeys, $patValues, $replaceTabs, $extensions);
                } else {
                    self::cloneFile($src_path . '/' . $file, $dst_path . '/' . $file, $patKeys, $patValues, $replaceTabs, $extensions);
                }
            }
        }
        \closedir($dir);
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @param array $patKeys
     * @param array $patValues
     * @param bool  $replaceTabs
     */
    private static function cloneFile($src_file, $dst_file, $patKeys, $patValues, $replaceTabs, $extensions): void
    {
        $replace_code = false;
        if (\in_array(\mb_strtolower(\pathinfo($src_file, PATHINFO_EXTENSION)), $extensions)) {
            $replace_code = true;
        }
        if (\mb_strpos($dst_file, \basename(__FILE__)) > 0) {
            //skip myself
            $replace_code = false;
        }
        if ($replace_code) {
            // file, read it and replace text
            $content = \file_get_contents($src_file);
            if ($replaceTabs) {
                $content = \preg_replace("/[\t]+/", '    ', $content);
            } else {
                $content = \str_replace($patKeys, $patValues, $content);
            }
            //check file name whether it contains replace code
            $path_parts = \pathinfo($dst_file);
            $path = $path_parts['dirname'];
            $file =  $path_parts['basename'];
            $dst_file = $path . '/' . \str_replace($patKeys, $patValues, $file);
            \file_put_contents($dst_file, $content);
        } else {
            \copy($src_file, $dst_file);
        }
    }

    /**
     * get form with all existing modules
     * @param bool $action
     * @return \XoopsSimpleForm
     */
    public static function getFormModulesFq($action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Module
        $modulesSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_DEVTOOLS_FQ_MODULE, 'fq_module', '');
        $modulesArr   = \XoopsLists::getModulesList();
        $modulesSelect->addOption('', ' ');
        foreach ($modulesArr as $mod) {
            $modulesSelect->addOption($mod, $mod);
        }
        $form->addElement($modulesSelect, true);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'fq'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));

        return $form;
    }

    /**
     * get form with all existing modules
     * @param bool $action
     * @return \XoopsSimpleForm
     */
    public static function getFormModulesCl($action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Module
        $modulesSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_DEVTOOLS_CL_MODULE, 'cl_module', '');
        $modulesArr   = \XoopsLists::getModulesList();
        $modulesSelect->addOption('', ' ');
        foreach ($modulesArr as $mod) {
            $modulesSelect->addOption($mod, $mod);
        }
        $form->addElement($modulesSelect, true);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'check_lang'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));

        return $form;
    }

    /**
     * get form with all existing modules
     * @param bool $action
     * @return \XoopsSimpleForm
     */
    public static function getFormModulesTab($action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Module
        $modulesSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_DEVTOOLS_TAB_MODULE, 'tab_module', '');
        $modulesArr   = \XoopsLists::getModulesList();
        $modulesSelect->addOption('', ' ');
        foreach ($modulesArr as $mod) {
            $modulesSelect->addOption($mod, $mod);
        }
        $form->addElement($modulesSelect, true);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'tab_replacer'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));

        return $form;
    }

    /**
     * get form with all existing modules
     * @param bool $action
     * @return \XoopsSimpleForm
     */
    public static function getFormModulesRemovePrefix($dst_path, $action = false)
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsSimpleForm('', 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Select Module
        $modulesSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_DEVTOOLS_RP_MODULE, 'rp_module', '');
        $modulesArr   = \XoopsLists::getModulesList();
        $modulesSelect->addOption('', ' ');
        foreach ($modulesArr as $mod) {
            $modulesSelect->addOption($mod, $mod);
        }
        $form->addElement($modulesSelect, true);
        $destradioSelect = new \XoopsFormRadio(\_AM_MODULEBUILDER_DEVTOOLS_RP_DEST, 'rp_dest', 1);
        $destradioSelect->addOption('1', \str_replace('%s', $dst_path, \_AM_MODULEBUILDER_DEVTOOLS_RP_DEST1));
        $destradioSelect->addOption('2', \_AM_MODULEBUILDER_DEVTOOLS_RP_DEST2);
        $form->addElement($destradioSelect);
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'remove_prefix'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));

        return $form;
    }
}

<?php

namespace XoopsModules\Modulebuilder\Files\Includes;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

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

/**
 * Class IncludeCommon.
 */
class IncludeCommon extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $xc = null;

    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @var mixed
     */
    private $hc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->hc  = Modulebuilder\Files\CreateHtmlCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return IncludeCommon
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     * @param string $module
     * @param object $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @param $modDirname
     * @param $const
     * @param $desc
     * @return string
     */
    private function getCommonDefines($modDirname, $const, $desc)
    {
        $stuModDirname = \mb_strtoupper($modDirname);

        return "\define('{$stuModDirname}_{$const}', {$desc});\n";
    }

    /**
     * @private function getCommonCode
     * @param \XoopsObject $module
     * @return string
     */
    private function getCommonCode($module)
    {
        $table                   = $this->getTable();
        $moduleDirname           = $module->getVar('mod_dirname');
        $stuModuleDirname        = \mb_strtoupper($moduleDirname);
        $moduleAuthor            = $module->getVar('mod_author');
        $moduleAuthorWebsiteName = $module->getVar('mod_author_website_name');
        $moduleAuthorWebsiteUrl  = $module->getVar('mod_author_website_url');
        $moduleAuthorImage       = \str_replace(' ', '', \mb_strtolower($moduleAuthor));

        $contIf = $this->pc->getPhpCodeDefine('XOOPS_ICONS32_PATH', "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'", "\t");
        $ret    = $this->pc->getPhpCodeConditions("!\defined('XOOPS_ICONS32_PATH')", '','', $contIf, false);
        $contIf = $this->pc->getPhpCodeDefine('XOOPS_ICONS32_URL', "XOOPS_URL . '/Frameworks/moduleclasses/icons/32'", "\t");
        $ret    .= $this->pc->getPhpCodeConditions("!\defined('XOOPS_ICONS32_URL')", '','', $contIf, false);
        $ret    .= $this->getCommonDefines($moduleDirname, 'DIRNAME', "'{$moduleDirname}'");
        $ret    .= $this->getCommonDefines($moduleDirname, 'PATH', "XOOPS_ROOT_PATH . '/modules/' . {$stuModuleDirname}_DIRNAME");
        $ret    .= $this->getCommonDefines($moduleDirname, 'URL', "XOOPS_URL . '/modules/' . {$stuModuleDirname}_DIRNAME");
        $ret    .= $this->getCommonDefines($moduleDirname, 'ICONS_PATH', "{$stuModuleDirname}_PATH . '/assets/icons'");
        $ret    .= $this->getCommonDefines($moduleDirname, 'ICONS_URL', "{$stuModuleDirname}_URL . '/assets/icons'");
        $ret    .= $this->getCommonDefines($moduleDirname, 'IMAGE_PATH', "{$stuModuleDirname}_PATH . '/assets/images'");
        $ret    .= $this->getCommonDefines($moduleDirname, 'IMAGE_URL', "{$stuModuleDirname}_URL . '/assets/images'");
        $ret    .= $this->getCommonDefines($moduleDirname, 'UPLOAD_PATH', "XOOPS_UPLOAD_PATH . '/' . {$stuModuleDirname}_DIRNAME");
        $ret    .= $this->getCommonDefines($moduleDirname, 'UPLOAD_URL', "XOOPS_UPLOAD_URL . '/' . {$stuModuleDirname}_DIRNAME");

        if (\is_object($table)) {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            $fieldElement = [];
            foreach (\array_keys($fields) as $f) {
                $fieldElement[] = $fields[$f]->getVar('field_element');
            }
        }
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_FILES_PATH', "{$stuModuleDirname}_UPLOAD_PATH . '/files'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_FILES_URL', "{$stuModuleDirname}_UPLOAD_URL . '/files'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_IMAGE_PATH', "{$stuModuleDirname}_UPLOAD_PATH . '/images'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_IMAGE_URL', "{$stuModuleDirname}_UPLOAD_URL . '/images'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_SHOTS_PATH', "{$stuModuleDirname}_UPLOAD_PATH . '/images/shots'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'UPLOAD_SHOTS_URL', "{$stuModuleDirname}_UPLOAD_URL . '/images/shots'");
        $ret     .= $this->getCommonDefines($moduleDirname, 'ADMIN', "{$stuModuleDirname}_URL . '/admin/index.php'");
        $ret     .= $this->xc->getXcEqualsOperator('$localLogo', "{$stuModuleDirname}_IMAGE_URL . '/{$moduleAuthorImage}_logo.png'");
        $ret     .= $this->pc->getPhpCodeCommentLine('Module Information');

        $img     = $this->hc->getHtmlImage('" . $localLogo . "', $moduleAuthorWebsiteName);
        $anchor  = $this->hc->getHtmlAnchor($moduleAuthorWebsiteUrl, $img, $moduleAuthorWebsiteName, '_blank', '', '', '', "\n");
        $replace = $this->xc->getXcEqualsOperator('$copyright', '"' . $anchor . '"');
        $ret     .= \str_replace("\n", '', $replace) . PHP_EOL;
        $ret     .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsrequest', true);
        $ret     .= $this->pc->getPhpCodeIncludeDir("{$stuModuleDirname}_PATH", 'include/functions', true);

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getCommonCode($module);
        $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

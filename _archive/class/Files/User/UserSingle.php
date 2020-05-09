<?php

namespace XoopsModules\Modulebuilder\Files\User;

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
 * Class UserSingle.
 */
class UserSingle extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserSingle
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
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserSingleHeader
     * @param $moduleDirname
     * @param $table
     * @param $fields
     * @return string
     */
    private function getUserSingleHeader($moduleDirname, $table, $fields)
    {
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret .= $this->getInclude();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }
        if (1 == $table->getVar('table_category')) {
            $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
            $ret        .= $xc->getXcXoopsRequest($ccFieldPid, (string)$fieldPid, '0', 'Int');
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, (string)$fieldId, '0', 'Int');
        $ret       .= $uxc->getUserTplMain($moduleDirname, 'single');
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $xc->getXcXoThemeAddStylesheet();

        return $ret;
    }

    /**
     * @public function getUserSingleBody
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSingleBody($moduleDirname, $tableName, $language)
    {
        $ret = <<<'EOT'

EOT;
        $ret .= $this->getSimpleString('$keywords = array();');

        return $ret;
    }

    /**
     * @private function getUserSingleFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserSingleFooter($moduleDirname, $tableName, $language)
    {
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc              = UserXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ret              = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uxc->getUserBreadcrumbs($language, $stuTableName);
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uxc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uxc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret              .= $xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret              .= $xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret              .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret              .= $this->getInclude('footer');

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
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId       = $table->getVar('table_id');
        $tableMid      = $table->getVar('table_mid');
        $tableName     = $table->getVar('table_name');
        $fields        = $this->getTableFields($tableMid, $tableId);
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserSingleHeader($moduleDirname, $table, $fields);
        $content       .= $this->getUserSingleBody($module, $tableName, $language);
        $content       .= $this->getUserSingleFooter($moduleDirname, $tableName, $language);
        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

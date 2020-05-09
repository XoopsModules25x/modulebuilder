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
 * Class UserIndex.
 */
class UserIndex extends Files\CreateFile
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
     * @return UserIndex
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
     * @param        $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplateHeaderFile
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplateHeaderFile($moduleDirname)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();
        $ret = $this->getInclude();
        $ret .= $uxc->getUserTplMain($moduleDirname);
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $xc->getXcXoThemeAddStylesheet();
        $ret .= $pc->getPhpCodeArray('keywords', null, false, '');

        return $ret;
    }

    /**
     * @private  function getBodyCategoriesIndex
     * @param $tableMid
     * @param $tableId
     * @param $tableName
     * @param $tableSoleName
     * @param $tableFieldname
     * @return string
     */
    private function getBodyCategoriesIndex($tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname)
    {
        // Fields
        $fields        = $this->getTableFields($tableMid, $tableId);
        $fieldParentId = [];
        foreach (array_keys($fields) as $f) {
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
        }
        $ret = '';
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        if (in_array(1, $fieldParentId)) {
            $ret .= $xc->getXcHandlerCountObj($tableName);
            $ret .= $pc->getPhpCodeCommentLine('If there are ', $tableName);
            $ret .= $this->getSimpleString('$count = 1;');

            $contentIf = $xc->getXcHandlerAllObj($tableName, '', 0, 0, "\t");
            $contentIf .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', "\t");
            //$contentIf .= $cc->getClassXoopsObjectTree('mytree', $tableName, $fieldId, $fieldParent, "\t");
            $contentIf .= $pc->getPhpCodeArray($tableName, "\t");
            $foreach   = $xc->getXcGetValues($tableName, $tableSoleName . 'Values', $tableFieldname, false, "\t\t");
            $foreach   .= $pc->getPhpCodeArray('acount', ["'count'", '$count']);
            $foreach   .= $pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName . 'Values', '$acount');
            $foreach   .= $this->getSimpleString('++$count;', "\t\t");
            $contentIf .= $pc->getPhpCodeForeach("{$tableName}All", true, false, $tableFieldname, $foreach, "\t");
            $contentIf .= $xc->getXcXoopsTplAssign($tableName, '$' . $tableName, true, "\t");
            $contentIf .= $pc->getPhpCodeUnset($tableName, "\t");
            $getConfig = $xc->getXcGetConfig('numb_col');
            $contentIf .= $xc->getXcXoopsTplAssign('numb_col', $getConfig, true, "\t");
            $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contentIf, false);
            $ret       .= $pc->getPhpCodeUnset('count');
        }
        unset($fieldParentId);

        return $ret;
    }

    /**
     * @private function getBodyPagesIndex
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfTableName     = ucfirst($tableName);
        $ret              = $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret              .= $xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret              .= $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcHandlerCountObj($tableName);
        $ret              .= $xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count");
        $ret              .= $this->getSimpleString('$count = 1;');
        $condIf           = $xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, "\t");
        $userpager        = $xc->getXcGetConfig('userpager');
        $condIf           .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int', false, "\t");
        $condIf           .= $xc->getXcHandlerAllObj($tableName, '', '$start', '$limit', "\t");
        $condIf           .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, "\t");
        $condIf           .= $pc->getPhpCodeArray($tableName, null, false, "\t");
        $foreach          = $xc->getXcGetValues($tableName, $tableSoleName, 'i', false, "\t\t");
        $foreach          .= $pc->getPhpCodeArray('acount', ["'count'", '$count']);
        $foreach          .= $pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName, '$acount');
        $table            = $this->getTable();
        // Fields
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach  .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, "\t\t");
        $foreach  .= $this->getSimpleString('++$count;', "\t\t");
        $condIf   .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf   .= $xc->getXcXoopsTplAssign($tableName, '$' . $tableName, true, "\t");
        $condIf   .= $pc->getPhpCodeUnset($tableName, "\t");
        $condIf   .= $xc->getXcPageNav($tableName, "\t");
        $thereare = $pc->getPhpCodeSprintf("{$language}INDEX_THEREARE", "\${$tableName}Count");
        $condIf   .= $xc->getXcXoopsTplAssign('lang_thereare', $thereare, true, "\t");
        $divideby = $xc->getXcGetConfig('divideby');
        $condIf   .= $xc->getXcXoopsTplAssign('divideby', $divideby, true, "\t");
        $numb_col  = $xc->getXcGetConfig('numb_col');
        $condIf   .= $xc->getXcXoopsTplAssign('numb_col', $numb_col, true, "\t");

        $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);
        $ret       .= $pc->getPhpCodeUnset('count');
        $tableType = $xc->getXcGetConfig('table_type');
        $ret       .= $xc->getXcXoopsTplAssign('table_type', $tableType);

        return $ret;
    }

    /**
     * @private  function getUserPagesFooter
     * @param $moduleDirname
     * @param $language
     * @return string
     */
    private function getUserIndexFooter($moduleDirname, $language)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc              = UserXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uxc->getUserBreadcrumbs($language);
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uxc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uxc->getUserMetaDesc($moduleDirname, $language);
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
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $module        = $this->getModule();
        $tables        = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module, null);
        $content       .= $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $content       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $content       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $content       .= $this->getTemplateHeaderFile($moduleDirname);
        foreach (array_keys($tables) as $t) {
            $tableId         = $tables[$t]->getVar('table_id');
            $tableMid        = $tables[$t]->getVar('table_mid');
            $tableName       = $tables[$t]->getVar('table_name');
            $tableSoleName   = $tables[$t]->getVar('table_solename');
            $tableCategory[] = $tables[$t]->getVar('table_category');
            $tableFieldname  = $tables[$t]->getVar('table_fieldname');
            $tableIndex[]    = $tables[$t]->getVar('table_index');
            if (in_array(1, $tableCategory, true) && in_array(1, $tableIndex)) {
                $content .= $this->getBodyCategoriesIndex($tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname);
            }
            if (in_array(0, $tableCategory, true) && in_array(1, $tableIndex)) {
                $content .= $this->getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $language);
            }
        }
        $content .= $this->getUserIndexFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

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
     * @var mixed
     */
    private $uxc = null;

    /**
     * @var mixed
     */
    private $xc = null;

    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = Modulebuilder\Files\User\UserXoopsCode::getInstance();
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
     * @private function getUserIndexHeader
     * @param $language
     * @param $moduleDirname
     *
     * @return string
     */
    private function getUserIndexHeader($language, $moduleDirname)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);

        $ret = $this->getInclude();
        $ret .= $this->uxc->getUserTplMain($moduleDirname);
        $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcXoThemeAddStylesheet();
        $ret .= $this->pc->getPhpCodeCommentLine('Keywords');
        $ret .= $this->pc->getPhpCodeArray('keywords', null, false, '');
        $ret .= $this->uxc->getUserBreadcrumbs($language);
        $ret .= $this->pc->getPhpCodeCommentLine('Paths');
        $ret .= $this->xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");

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
        foreach (\array_keys($fields) as $f) {
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
        }
        $ret = '';
        if (\in_array(1, $fieldParentId)) {
            $ret .= $this->xc->getXcHandlerCountObj($tableName);
            $ret .= $this->pc->getPhpCodeCommentLine('If there are ', $tableName);
            $ret .= $this->getSimpleString('$count = 1;');

            $contentIf = $this->xc->getXcHandlerAllObj($tableName, '', 0, 0, "\t");
            $contentIf .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', "\t");
            //$contentIf .= $cc->getClassXoopsObjectTree('mytree', $tableName, $fieldId, $fieldParent, "\t");
            $contentIf .= $this->pc->getPhpCodeArray($tableName, "\t");
            $foreach   = $this->xc->getXcGetValues($tableName, $tableSoleName . 'Values', $tableFieldname, false, "\t\t");
            $foreach   .= $this->pc->getPhpCodeArray('acount', ["'count'", '$count']);
            $foreach   .= $this->pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName . 'Values', '$acount');
            $foreach   .= $this->getSimpleString('++$count;', "\t\t");
            $contentIf .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, $tableFieldname, $foreach, "\t");
            $contentIf .= $this->xc->getXcXoopsTplAssign($tableName, '$' . $tableName, true, "\t");
            $contentIf .= $this->pc->getPhpCodeUnset($tableName, "\t");
            $getConfig = $this->xc->getXcGetConfig('numb_col');
            $contentIf .= $this->xc->getXcXoopsTplAssign('numb_col', $getConfig, true, "\t");
            $ret       .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contentIf, false);
            $ret       .= $this->pc->getPhpCodeUnset('count');
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
        $ucfTableName     = \ucfirst($tableName);
        $table            = $this->getTable();
        $fields           = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));

        $ret       = $this->pc->getPhpCodeCommentLine('Tables');
        $ret       .= $this->xc->getXcHandlerCountObj($tableName);
        $ret       .= $this->xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count");
        $ret       .= $this->getSimpleString('$count = 1;');
        $condIf    = $this->xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, "\t");
        $userpager = $this->xc->getXcGetConfig('userpager');
        $condIf    .= $this->xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int', false, "\t");
        $condIf    .= $this->xc->getXcHandlerAllObj($tableName, '', '$start', '$limit', "\t");
        $condIf    .= $this->pc->getPhpCodeCommentLine('Get All', $ucfTableName, "\t");
        $condIf    .= $this->pc->getPhpCodeArray($tableName, null, false, "\t");
        $foreach   = $this->xc->getXcGetValues($tableName, $tableSoleName, 'i', false, "\t\t");
        $foreach   .= $this->pc->getPhpCodeArray('acount', ["'count'", '$count']);
        $foreach   .= $this->pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName, '$acount');
        // Fields
        $fieldMain = '';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach  .= $this->xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, "\t\t");
        $foreach  .= $this->getSimpleString('++$count;', "\t\t");
        $condIf   .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf   .= $this->xc->getXcXoopsTplAssign($tableName, '$' . $tableName, true, "\t");
        $condIf   .= $this->pc->getPhpCodeUnset($tableName, "\t");
        $condIf   .= $this->xc->getXcPageNav($tableName, "\t");
        $thereare = $this->pc->getPhpCodeSprintf("{$language}INDEX_THEREARE", "\${$tableName}Count");
        $condIf   .= $this->xc->getXcXoopsTplAssign('lang_thereare', $thereare, true, "\t");
        $divideby = $this->xc->getXcGetConfig('divideby');
        $condIf   .= $this->xc->getXcXoopsTplAssign('divideby', $divideby, true, "\t");
        $numb_col  = $this->xc->getXcGetConfig('numb_col');
        $condIf   .= $this->xc->getXcXoopsTplAssign('numb_col', $numb_col, true, "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);
        $ret       .= $this->pc->getPhpCodeUnset('count');
        $tableType = $this->xc->getXcGetConfig('table_type');
        $ret       .= $this->xc->getXcXoopsTplAssign('table_type', $tableType);

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
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ret = $this->pc->getPhpCodeCommentLine('Keywords');
        $ret .= $this->uxc->getUserMetaKeywords($moduleDirname);
        $ret .= $this->pc->getPhpCodeUnset('keywords');
        $ret .= $this->pc->getPhpCodeCommentLine('Description');
        $ret .= $this->uxc->getUserMetaDesc($moduleDirname, $language);
        $ret .= $this->xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret .= $this->xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module           = $this->getModule();
        $tables           = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename         = $this->getFileName();
        $moduleDirname    = $module->getVar('mod_dirname');
        $language         = $this->getLanguage($moduleDirname, 'MA');

        $content = $this->getHeaderFilesComments($module, null);
        $content .= $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $content .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $content .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $content .= $this->getUserIndexHeader($language, $moduleDirname);

        foreach (\array_keys($tables) as $t) {
            $tableId         = $tables[$t]->getVar('table_id');
            $tableMid        = $tables[$t]->getVar('table_mid');
            $tableName       = $tables[$t]->getVar('table_name');
            $tableSoleName   = $tables[$t]->getVar('table_solename');
            $tableCategory   = $tables[$t]->getVar('table_category');
            $tableFieldname  = $tables[$t]->getVar('table_fieldname');
            $tableIndex      = $tables[$t]->getVar('table_index');
            if (1 == $tableCategory && 1 == $tableIndex) {
                $content .= $this->getBodyCategoriesIndex($tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname);
            }
            if (0 == $tableCategory && 1 == $tableIndex) {
                $content .= $this->getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $language);
            }
        }
        $content .= $this->getUserIndexFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

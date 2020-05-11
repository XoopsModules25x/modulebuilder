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
 * Class UserPages.
 */
class UserPages extends Files\CreateFile
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
     * @return UserPages
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
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserPagesHeader
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $uxc->getUserTplMain($moduleDirname, $tableName);
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsRequest('op   ', 'op', 'list', 'String');
        $ret       .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $xc->getXcGetConfig('userpager');
        $ret       .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '0', 'Int');
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $xc->getXcXoThemeAddStylesheet();
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret       .= $xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $pc->getPhpCodeArray('keywords', null, false, '');
        $ret       .= $pc->getPhpCodeBlankLine();

        return $ret;
    }

    /**
     * @private function getUserPagesList
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     * @return string
     */
    private function getUserPagesList($tableName, $fieldId, $fieldMain, $t = '')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ucfTableName     = ucfirst($tableName);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);

        $critName  = 'cr' . $ucfTableName;
        $ret       = $xc->getXcCriteriaCompo($critName, $t);
        $crit      = $xc->getXcCriteria('', "'{$fieldId}'", "\${$ccFieldId}",'',true);
        $contIf    = $xc->getXcCriteriaAdd($critName, $crit, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName, $t);
        $ret       .= $xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count", '', $t);
        $ret       .= $xc->getXcCriteriaSetStart($critName, '$start', $t);
        $ret       .= $xc->getXcCriteriaSetLimit($critName, '$limit', $t);
        $ret       .= $xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$' . $critName, $t);
        $condIf    = $pc->getPhpCodeArray($tableName, null, false, $t . "\t");
        $condIf    .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, $t . "\t");
        $foreach   = $xc->getXcGetValues($tableName, $tableName . '[]', 'i', false, $t . "\t\t");
        $foreach   .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, $t . "\t\t");
        $condIf    .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t . "\t");
        $condIf    .= $xc->getXcXoopsTplAssign($tableName, "\${$tableName}", true, $t . "\t");
        $condIf    .= $pc->getPhpCodeUnset($tableName, $t . "\t");
        $condIf    .= $xc->getXcPageNav($tableName, $t . "\t");
        $tableType = $xc->getXcGetConfig('table_type');
        $condIf    .= $xc->getXcXoopsTplAssign('type', $tableType, true, $t . "\t");
        $divideby  = $xc->getXcGetConfig('divideby');
        $condIf    .= $xc->getXcXoopsTplAssign('divideby', $divideby, true, $t . "\t");
        $numbCol   = $xc->getXcGetConfig('numb_col');
        $condIf    .= $xc->getXcXoopsTplAssign('numb_col', $numbCol, true, $t . "\t");

        $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, false, $t);

        return $ret;
    }

    /**
     * @public function getUserSubmitSave
     * @param string $moduleDirname
     * @param        $fields
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableSubmit
     * @param $tablePermissions
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret                = $pc->getPhpCodeCommentLine('Security Check', '', $t);
        $xoopsSecurityCheck = $xc->getXcXoopsSecurityCheck();
        $securityError      = $xc->getXcXoopsSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret                .= $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf             = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret                .= $xc->getXcHandlerCreateObj($tableName, $t);
        $ret                .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t);
        $ret                .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert             = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj', 'Handler');
        $countUploader      = 0;
        $fieldId            = '';
        $ccFieldId          = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
                $ccFieldId = $this->getCamelCase($fieldId, false, true);
            }
            if ($fields[$f]->getVar('field_type') >= 10 && $fields[$f]->getVar('field_type') <= 14) {
                $countUploader++;
            }
        }
        $contentInsert = '';
        if (1 == $tablePermissions) {
            $ucfTableName  = ucfirst($tableName);
            $ucfFieldId    = $this->getCamelCase($fieldId, true);
            $contentInsert .= $xc->getXcEqualsOperator("\$new{$ucfFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", null, $t . "\t");
            $contentInsert .= $pc->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t . "\t");
            $contentInsert .= $xc->getXcXoopsHandler('groupperm', $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'approve_' . $tableName);
        }

        if ($countUploader > 0) {
            $errIf     = $xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \${$ccFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse   = $xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $confirmOk = $pc->getPhpCodeConditions("''", ' !== ', '$uploaderErrors', $errIf, $errElse, $t . "\t");
        } else {
            $confirmOk = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $contentInsert .= $confirmOk;
        $ret           .= $pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->display()', true, $t);

        return $ret;
    }

    /**
     * @private function getPermissionsSave
     * @param $moduleDirname
     * @param string $perm
     *
     * @return string
     */
    private function getPermissionsSave($moduleDirname, $perm = 'view')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ret     = $pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret     .= $xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', '$permId', false, "\t\t\t");
        $content = $xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', '$mid', false, "\t\t\t\t\t");
        $foreach = $pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @public function getUserPagesNew
     * @param        $tableName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesNew($tableName, $language, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret    = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret    .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret    .= $xc->getXcCommonPagesNew($tableName, $t);

        return $ret;
    }

    /**
     * @public function getUserPagesEdit
     * @param        $tableName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesEdit($moduleDirname, $tableName, $fieldId, $language, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcCommonPagesEdit($tableName, $ccFieldId, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesDelete
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getUserPagesDelete($tableName, $language, $fieldId, $fieldMain, $t = '')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcCommonPagesDelete($language, $tableName, $fieldId, $fieldMain, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesBroken
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getUserPagesBroken($tableName, $language, $fieldId, $fieldSatus, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);

        $ret    .= $this->getSimpleString('$error = false;', $t);
        $ret    .= $this->getSimpleString("\$errorMessage = '';", $t);
        $ret    .= $pc->getPhpCodeCommentLine('Test first the validation', null, $t);
        $ret    .= $xc->getXcXoopsLoad('captcha', $t);
        $ret    .= $xc->getXcXoopsCaptcha($t);
        $contIf = $xc->getXcEqualsOperator('$errorMessage', "\$xoopsCaptcha->getMessage().'<br>'", '.', $t . "\t");
        $contIf .= $xc->getXcXoopsTplAssign('error_message', '$errorMessage', true, $t . "\t");
        $contIf .= $this->getSimpleString('break;', $t . "\t");
        $ret    .= $pc->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', $contIf, false, $t);

        $ret .= $xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $ret .= $xc->getXcSetVarObj($tableName, $fieldSatus, 'Constants::STATUS_BROKEN', $t);

        $ret           .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert         = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $redirectHeader = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t . "\t");
        $ret           .= $pc->getPhpCodeConditions($insert, '', '', $redirectHeader, false, $t);
        $ret .= $pc->getPhpCodeBlankLine();
        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->display()', true, $t);
        $ret .= $pc->getPhpCodeBlankLine();

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $language)
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ret              = $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uxc->getUserBreadcrumbs($language, $stuTableName);
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uxc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uxc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret              .= $xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret              .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret              .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @private function getUserPagesSwitch
     * @param $moduleDirname
     * @param $tableId
     * @param $tableMid
     * @param $tableName
     * @param $tableSoleName
     * @param $tableSubmit
     * @param $tablePermissions
     * @param $language
     * @param $t
     * @return string
     */
    private function getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldSatus, $language, $t)
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $fields = $this->getTableFields($tableMid, $tableId);
        $cases['show'] = [];
        $cases['list'] = [$this->getUserPagesList($tableName, $fieldId, $fieldMain, "\t\t")];
        if (1 == $tableSubmit) {
            $cases['save']   = [$this->getUserPagesSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, "\t\t")];
            $cases['new']    = [$this->getUserPagesNew($tableName, $language, "\t\t")];
            $cases['edit']   = [$this->getUserPagesEdit($moduleDirname, $tableName, $fieldId, $language, "\t\t")];
            $cases['delete'] = [$this->getUserPagesDelete($tableName, $language, $fieldId, $fieldMain,"\t\t")];
        }
        if (1 == $tableBroken) {
            $cases['broken']  = [$this->getUserPagesBroken($tableName, $language, $fieldId, $fieldSatus,"\t\t")];
        }

        return $xc->getXcSwitch('op', $cases, true, false);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module           = $this->getModule();
        $table            = $this->getTable();
        $tableId          = $table->getVar('table_id');
        $tableMid         = $table->getVar('table_mid');
        $tableName        = $table->getVar('table_name');
        $tableSubmit      = $table->getVar('table_submit');
        $tablePermissions = $table->getVar('table_permissions');
        $tableSoleName    = $table->getVar('table_solename');
        $tableBroken      = $table->getVar('table_broken');
        $filename         = $this->getFileName();
        $moduleDirname    = $module->getVar('mod_dirname');
        $language         = $this->getLanguage($moduleDirname, 'MA');
        // Fields
        $fieldId    = '';
        $fieldMain  = '';
        $fieldSatus = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
            if (16 == $fields[$f]->getVar('field_element')) {
                $fieldSatus = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserPagesHeader($moduleDirname, $tableName, $fieldId);
        $content       .= $this->getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldSatus, $language, "\t");
        $content       .= $this->getUserPagesFooter($moduleDirname, $tableName, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

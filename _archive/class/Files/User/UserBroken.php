<?php

namespace XoopsModules\Tdmcreate\Files\User;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
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
 * Class UserBroken.
 */
class UserBroken extends Files\CreateFile
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
     * @return UserBroken
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
     * @param       $module
     * @param mixed $table
     * @param       $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getUserBrokenHeader
     * @param $moduleDirname
     * @param $fields
     * @return string
     */
    public function getUserBrokenHeader($moduleDirname, $fields)
    {
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uxc       = UserXoopsCode::getInstance();
        $fieldId   = $xc->getXcSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $xc->getXcXoopsRequest('op', 'op', 'list');
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');
        $ret       .= $pc->getPhpCodeCommentLine('Template');
        $ret       .= $uxc->getUserTplMain($moduleDirname, 'broken');
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $xc->getXcXoThemeAddStylesheet();
        $ret       .= $pc->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf    = $xc->getXcRedirectHeader('index', '', '2', '_NOPERM', true, "\t");
        $condIf    .= $this->getSimpleString('exit();', "\t");
        $ret       .= $pc->getPhpCodeConditions('$permSubmit', ' === ', 'false', $condIf);

        return $ret;
    }

    /**
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     * @param $t
     * @return string
     */
    public function getUserBrokenForm($tableName, $language, $t)
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uxc = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Navigation','', $t);
        $ret .= $xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER", null, $t);
        $ret .= $xc->getXcXoopsTplAssign('navigation', '$navigation', true, $t);
        $ret .= $pc->getPhpCodeCommentLine('Title of page', null, $t);
        $ret .= $xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'", null, $t);
        $ret .= $xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', $t);
        $ret .= $xc->getXcXoopsTplAssign('xoops_pagetitle', '$title', true, $t);
        $ret .= $pc->getPhpCodeCommentLine('Description', null, $t);
        $ret .= $uxc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER', $t);
        $ret .= $pc->getPhpCodeCommentLine('Form Create', null, $t);
        $ret .= $xc->getXcHandlerCreateObj($tableName, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @public function getUserBrokenSave
     * @param $moduleDirname
     * @param $fields
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @param $t
     * @return string
     */
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSoleName, $language, $t)
    {
        $xc                 = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc                 = Tdmcreate\Files\CreatePhpCode::getInstance();
        $ret                = $pc->getPhpCodeCommentLine('Security Check','',$t);
        $xoopsSecurityCheck = $xc->getXcXoopsSecurityCheck();
        $securityError      = $xc->getXcXoopsSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret                .= $xc->getXcHandlerCreateObj($tableName, $t);

        $ret .= $this->getSimpleString('$error = false;', $t);
        $ret .= $this->getSimpleString("\$errorMessage = '';", $t);
        $ret .= $pc->getPhpCodeCommentLine('Test first the validation', null, $t);
        $ret .= $xc->getXcXoopsLoad('captcha', $t);
        $ret .= $xc->getXcXoopsCaptcha($t);

        $ret .= $pc->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', "\t\t\t\$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';\n\t\t\t\$error = true;\n", false, $t);

        $ret .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t);

        $condElse      = $pc->getPhpCodeCommentLine('Insert Data', null, $t . "\t");
        $insert        = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $redirectHeader = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t . "\t\t");
        $condElse      .= $pc->getPhpCodeConditions($insert, '', '', $redirectHeader, false, $t . "\t");
        $assigne       = $xc->getXcXoopsTplAssign('error_message', '$errorMessage', true, $t . "\t");
        $ret           .= $pc->getPhpCodeConditions('$error', ' === ', 'true', $assigne, $condElse, $t);

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->display()', true, $t);

        return $ret;
    }

    /**
     * @private function getUserSubmitSwitch
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return bool|string
     */
    private function getUserBrokenSwitch($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $xc       = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $table    = $this->getTable();
        $tableId  = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $fields   = $this->getTableFields($tableMid, $tableId);
        $cases    = [
            'form' => [$this->getUserBrokenForm($tableName, $language, "\t\t")],
            'save' => [$this->getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSoleName, $language, "\t\t")],
        ];

        return $xc->getXcSwitch('op', $cases, true, false);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $fields             = $this->getTableFields($tableMid, $tableId);
        $language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getHeaderFilesComments($module);
        $content            .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content            .= $this->getUserBrokenSwitch($moduleDirname, $tableName, $tableSoleName, $language);
        $content            .= $this->getInclude('footer');

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

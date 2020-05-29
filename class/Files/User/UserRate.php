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
 * Class UserRate.
 */
class UserRate extends Files\CreateFile
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
    private $uxc = null;
	
	
	/**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return UserRate
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
     * @private function getUserRateHeader
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    public function getUserRateHeader($moduleDirname, $tableName)
    {
        $ret = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret .= $this->getInclude();
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'form', 'Cmd');
        $ret .= $this->xc->getXcXoopsRequest('lid', 'lid', '', 'Int');
        $ret .= $this->uxc->getUserTplMain($moduleDirname, $tableName);
        $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcXoThemeAddStylesheet();

        return $ret;
    }

    /**
     * @private function getUserRateSwitch
     * @param $moduleDirname
     * @param $tableId
     * @param $tableMid
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases  = [
            'form' => [$this->getUserRateForm($tableName, $language)],
            'save' => [$this->getUserRateSave($moduleDirname, $fields, $tableName, $tableSoleName, $language)],
        ];

        return $this->xc->getXcSwitch('op', $cases, true, false, "\t");
    }

    /**
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserRateForm($tableName, $language)
    {
        $ret = $this->pc->getPhpCodeCommentLine('Navigation');
        $ret .= $this->xc->getXcEqualsOperator('$navigation', "{$language}RATE", null, "\t\t");
        $ret .= $this->xc->getXcXoopsTplAssign('navigation', '$navigation', true, "\t\t");
        $ret .= $this->pc->getPhpCodeCommentLine('Title of page', null, "\t\t");
        $ret .= $this->xc->getXcEqualsOperator('$title', "{$language}RATE . '&nbsp;-&nbsp;'", null, "\t\t");
        $ret .= $this->xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', "\t\t");
        $ret .= $this->xc->getXcXoopsTplAssign('xoops_pagetitle', '$title', true, "\t\t");
        $ret .= $this->pc->getPhpCodeCommentLine('Description', null, "\t\t");
        $ret .= $this->uxc->getUserAddMeta('description', $language, 'RATE', "\t\t");
        $ret .= $this->pc->getPhpCodeCommentLine('Form Create', null, "\t\t");
        $ret .= $this->xc->getXcHandlerCreateObj($tableName, "\t\t");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $this->xc->getXcXoopsTplAssign('form', '$form->render()', true, "\t\t");

        return $ret;
    }

    /**
     * @public function getUserRateSave
     * @param $moduleDirname
     * @param $fields
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    public function getUserRateSave($moduleDirname, $fields, $tableName, $tableSoleName, $language)
    {
        $ret                = $this->pc->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXcXoopsSecurityCheck();
        $securityError      = $this->xc->getXcXoopsSecurityErrors();
        $implode            = $this->pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, "\t\t\t");
        $ret                .= $this->pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t\t");
        $ret                .= $this->xc->getXcHandlerCreateObj($tableName, "\t\t");

        $ret .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, "\t\t");

        $ret       .= $this->pc->getPhpCodeCommentLine('Insert Data', null, "\t\t");
        $insert    = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj', true);
        $confirmOk = $this->xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, "\t\t\t");
        $ret       .= $this->pc->getPhpCodeConditions($insert, '', '', $confirmOk, false, "\t\t");

        $ret .= $this->pc->getPhpCodeCommentLine('Get Form Error', null, "\t\t");
        $ret .= $this->xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, "\t\t");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $this->xc->getXcXoopsTplAssign('form', '$form->render()', true, "\t\t");

        return $ret;
    }

    /**
     * @public function getUserRateFooter
     * @param $language
     * @return string
     */
    public function getUserRateFooter($language)
    {
        $ret              = $this->pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $this->uxc->getUserBreadcrumbs('RATE', $language);
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
        $module             = $this->getModule();
        //$table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        //$tableId            = $table->getVar('table_id');
        //$tableMid           = $table->getVar('table_mid');
        //$tableName          = $table->getVar('table_name');
        //$tableSoleName      = $table->getVar('table_solename');
        //$tableAutoincrement = $table->getVar('table_autoincrement');
        //$language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getHeaderFilesComments($module);
        //$content            .= $this->getUserRateHeader($moduleDirname, $tableName);
        //$content            .= $this->getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
        //$content            .= $this->getUserRateFooter($language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

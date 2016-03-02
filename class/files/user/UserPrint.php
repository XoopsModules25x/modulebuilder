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
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: UserPrint.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserPrint.
 */
class UserPrint extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $uc = null;

    /*
    * @var string
    */
    private $xc = null;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserPrint
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function write
    *  @param string $module
    *  @param mixed $table
    *  @param string $filename
    */
    /**
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

    /*
    *  @public function getUserPrint
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    public function getUserPrint($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $rpFieldName = $fieldName;
            if (strpos($fieldName, '_')) {
                $str = strpos($fieldName, '_');
                if ($str !== false) {
                    $rpFieldName = substr($fieldName, $str + 1, strlen($fieldName));
                }
            }
            if ((0 == $f) && (1 == $this->table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            } else {
                if (1 == $fields[$f]->getVar('field_main')) {
                    $fieldMain = $fieldName; // fieldMain = fields parameters main field
                }
            }
            $ucfFieldName = ucfirst($fieldName);
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $stuLpFieldName = strtoupper($ccFieldId);
        $ret = $this->getInclude();
        $ret .= $this->xc->getXoopsCodeXoopsRequest("{$ccFieldId}", "{$fieldId}", '', 'Int');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXoopsCodeAddStylesheet();
        $redirectHeader = $this->xc->getXoopsCodeRedirectHeader("{$stuModuleDirname}_URL . '/index.php'", '', '2', "{$language}NO{$stuLpFieldName}", false, "\t");
        $ret .= $this->phpcode->getPhpCodeConditions("empty(\${$ccFieldId})", '', '', $redirectHeader);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Verify that the article is published');
        if (strstr($fieldName, 'published')) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Not yet', $fieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret .= $this->phpcode->getPhpCodeConditions("\${$ccFieldId}->getVar('{$fieldName}') == 0 || \${$ccFieldId}->getVar('{$fieldName}') > time()", '', '', $redirectHeader);
        }
        if (strstr($fieldName, 'expired')) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Expired', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret .= $this->phpcode->getPhpCodeConditions("\${$ccFieldId}->getVar('{$fieldName}') != 0 && \${$ccFieldId}->getVar('{$fieldName}') < time()", '', '', $redirectHeader);
        }
        if (strstr($fieldName, 'date')) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Date', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret .= $this->phpcode->getPhpCodeConditions("\${$ccFieldId}->getVar('{$fieldName}') != 0 && \${$ccFieldId}->getVar('{$fieldName}') < time()", '', '', $redirectHeader);
        }
        if (strstr($fieldName, 'time')) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Time', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret .= $this->phpcode->getPhpCodeConditions("\${$ccFieldId}->getVar('{$fieldName}') != 0 && \${$ccFieldId}->getVar('{$fieldName}') < time()", '', '', $redirectHeader);
        }
        $ret .= $this->xc->getXoopsCodeGet($tableName, "{$ccFieldId}", '', true);
        $gperm = $this->xc->getXoopsCodeCheckRight('!$gpermHandler', "{$moduleDirname}_view", "\${$ccFieldId}->getVat('{$fieldId}')", '$groups', "\$GLOBALS['xoopsModule']->getVar('mid')", true);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Verify permissions');
        $noPerm = $this->xc->getXoopsCodeRedirectHeader("{$stuModuleDirname}_URL . '/index.php'", '', '3', '_NOPERM', false, "\t");
        $noPerm .= $this->getSimpleString('exit();', "\t");
        $ret .= $this->phpcode->getPhpCodeConditions($gperm, '', '', $noPerm);
        $ret .= $this->xc->getXoopsCodeGetValues($tableName, $tableSoleName, '', true);
        $contentForeach = $this->xc->getXoopsCodeXoopsTplAppend('"{$k}"', '$v', "\t");
        $ret .= $this->phpcode->getPhpCodeForeach($tableSoleName, false, 'k', 'v', $contentForeach);
        $ret .= $this->xc->getXoopsCodeTplAssign('xoops_sitename', "\$GLOBALS['xoopsConfig']['sitename']");
        $getVar = $this->xc->getXoopsCodeGetVar('', $tableSoleName, $fieldMain, true);
        $stripTags = $this->phpcode->getPhpCodeStripTags('', $getVar.' - '."{$language}PRINT".' - '."\$GLOBALS['xoopsModule']->name()", true);
        $ret .= $this->xc->getXoopsCodeTplAssign('xoops_pagetitle', $stripTags);
        $ret .= $this->xc->getXoopsCodeTplDisplay($tableName.'_print.tpl');

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserPrint($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

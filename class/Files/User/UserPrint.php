<?php declare(strict_types=1);

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 */

/**
 * Class UserPrint.
 */
class UserPrint extends Files\CreateFile
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
     * @public function constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @return UserPrint
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
     * @param        $module
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, string $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getUserPrint
     * @param string $moduleDirname
     * @param string $language
     *
     * @return string
     */
    public function getUserPrint(string $moduleDirname, string $language)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $table            = $this->getTable();
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $fields           = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));

        $fieldId          = '';
        $fieldMain        = '';
        $fieldName        = '';
        $ucfFieldName     = '';
        foreach (\array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
            if ((0 == $f) && (1 == $this->table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            } else {
                if (1 == $fields[$f]->getVar('field_main')) {
                    $fieldMain = $fieldName; // fieldMain = fields parameters main field
                }
            }
            $ucfFieldName = \ucfirst($fieldName);
        }
        $ccFieldId      = $this->getCamelCase($fieldId, false, true);
        $ret            = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret            .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret            .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret            .= $this->getRequire();
        $ret            .= $this->pc->getPhpCodeIncludeDir("\XOOPS_ROOT_PATH . '/header.php'", '', true, true);
        $ret            .= $this->xc->getXcXoopsRequest($ccFieldId, (string)$fieldId, '', 'Int');
        $ret            .= $this->pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret            .= $this->xc->getXcXoThemeAddStylesheet();
        $redirectHeader = $this->xc->getXcRedirectHeader("\\{$stuModuleDirname}_URL . '/index.php'", '', '2', "{$language}INVALID_PARAM", false, "\t");
        $ret            .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $redirectHeader);

        $ret            .= $this->pc->getPhpCodeCommentLine('Get Instance of Handler');
        $ret            .= $this->xc->getXcHandlerLine($tableName);

        $ret            .= $this->getSimpleString('$currentuid = 0;');
        $condIf         = $this->getSimpleString('$currentuid = $xoopsUser->uid();', "\t");
        $ret            .= $this->pc->getPhpCodeConditions('isset($xoopsUser) && \is_object($xoopsUser)', '', '', $condIf);
        $ret            .= $this->xc->getXcXoopsHandler('groupperm');
        $ret            .= $this->xc->getXcXoopsHandler('member');
        $condIf         = $this->getSimpleString('$my_group_ids = [\XOOPS_GROUP_ANONYMOUS];', "\t");
        $condElse       = $this->getSimpleString('$my_group_ids = $memberHandler->getGroupsByUser($currentuid);', "\t");
        $ret            .= $this->pc->getPhpCodeConditions('0', ' === ', '$currentuid', $condIf, $condElse);

        $ret            .= $this->pc->getPhpCodeCommentLine('Verify that the article is published');
        if (false !== mb_strpos($fieldName, 'published')) {
            $ret            .= $this->pc->getPhpCodeCommentLine('Not yet', $fieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret            .= $this->pc->getPhpCodeConditions("\${$tableName}Handler->getVar('{$fieldName}') == 0 || \${$tableName}Handler->getVar('{$fieldName}') > \time()", '', '', $redirectHeader);
        }
        if (false !== mb_strpos($fieldName, 'expired')) {
            $ret            .= $this->pc->getPhpCodeCommentLine('Expired', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret            .= $this->pc->getPhpCodeConditions("\${$tableName}Handler->getVar('{$fieldName}') != 0 && \${$tableName}Handler->getVar('{$fieldName}') < \time()", '', '', $redirectHeader);
        }
        if (false !== mb_strpos($fieldName, 'date')) {
            $ret            .= $this->pc->getPhpCodeCommentLine('Date', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret            .= $this->pc->getPhpCodeConditions("\${$tableName}Handler->getVar('{$fieldName}') != 0 && \${$tableName}Handler->getVar('{$fieldName}') < \time()", '', '', $redirectHeader);
        }
        if (false !== mb_strpos($fieldName, 'time')) {
            $ret            .= $this->pc->getPhpCodeCommentLine('Time', $ucfFieldName);
            $redirectHeader .= $this->getSimpleString('exit();');
            $ret            .= $this->pc->getPhpCodeConditions("\${$tableName}Handler->getVar('{$fieldName}') != 0 && \${$tableName}Handler->getVar('{$fieldName}') < \time()", '', '', $redirectHeader);
        }
        $ret            .= $this->xc->getXcHandlerGet($tableName . 'Obj', $ccFieldId, '', $tableName . 'Handler');
        $tablenameObj   = $this->pc->getPhpCodeIsobject($tableName . 'Obj');
        $redirectError  = $this->xc->getXcRedirectHeader($tableName, '', '3', "{$language}INVALID_PARAM", true,  "\t");
        $ret            .= $this->pc->getPhpCodeConditions('!' . $tablenameObj, '', '', $redirectError);
        $gperm          = $this->xc->getXcCheckRight('!$grouppermHandler', "{$moduleDirname}_view_{$tableName}", "\${$tableName}Obj->getVar('{$fieldId}')", '$my_group_ids', "\$GLOBALS['xoopsModule']->getVar('mid')", true);
        $ret            .= $this->pc->getPhpCodeCommentLine('Verify permissions');
        $noPerm         = $this->xc->getXcRedirectHeader("\\{$stuModuleDirname}_URL . '/index.php'", '', '3', '\_NOPERM', false, "\t");
        $noPerm         .= $this->getSimpleString('exit();', "\t");
        $ret            .= $this->pc->getPhpCodeConditions($gperm, '', '', $noPerm);
        $ret            .= $this->xc->getXcGetValues($tableName, $tableSoleName . 'List', '', true, '', 'Obj');
        $ret            .= $this->xc->getXcXoopsTplAppend($tableName . '_list', '$' . $tableSoleName . 'List');
        $ret            .= $this->pc->getPhpCodeBlankLine();
        $ret            .= $this->xc->getXcXoopsTplAssign('xoops_sitename', "\$GLOBALS['xoopsConfig']['sitename']");
        $getVar         = $this->xc->getXcGetVar('', $tableName . 'Obj', $fieldMain, true);
        $stripTags      = $this->pc->getPhpCodeStripTags('', $getVar . " . ' - ' . " . "{$language}PRINT" . " . ' - ' . " . "\$GLOBALS['xoopsModule']->getVar('name')", true);
        $ret            .= $this->xc->getXcXoopsTplAssign('xoops_pagetitle', $stripTags);
        $ret            .= $this->xc->getXcXoopsTplDisplay($moduleDirname . '_' . $tableName . '_print.tpl', '', false);

        return $ret;
    }

    /**
     * @public function render
     * @return string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserPrint($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

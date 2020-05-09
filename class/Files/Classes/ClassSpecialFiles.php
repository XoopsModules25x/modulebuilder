<?php

namespace XoopsModules\Modulebuilder\Files\Classes;

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
 * tc module.
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
 * Class ClassSpecialFiles.
 */
class ClassSpecialFiles extends Files\CreateFile
{
        
    /**
     * "className" attribute of the files.
     *
     * @var mixed
     */
    public $className = null;
    
    
    /**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     *
     * @return bool|ClassSpecialFiles
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
     *
     * @param string $module
     * @param string $table
     * @param mixed  $tables
     * @param        $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @public function renderPermissionsHandler
     * @param null
     *
     * @return bool|string
     */
    public function getGlobalPerms($permId)
    {
        $pc             = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc             = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $module         = $this->getModule();
        $moduleDirname  = $module->getVar('mod_dirname');

        $returnTrue     = $this->getSimpleString("return true;", "\t\t\t");
        $right     = '';
        $cond      = '';
        $funcname  = '';
        $comment   = '';
        switch ($permId) {
            case 4:
                $comment  .= $pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalApprove', 'returns' => 'right for global approve', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $right    .= $xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 4, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalApprove';
                break;
            case 8:
                $comment  .= $pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalSubmit', 'returns' => 'right for global submit', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $cond     .= $pc->getPhpCodeConditions('$this->getGlobalApprove()', '', '', $returnTrue, false, "\t\t");
                $right    .= $xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 8, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalSubmit';
                break;
            case 16:
                $comment  .= $pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalView', 'returns' => 'right for global view', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $cond     .= $pc->getPhpCodeConditions('$this->getGlobalApprove()', '', '', $returnTrue, false, "\t\t");
                $cond     .= $pc->getPhpCodeConditions('$this->getGlobalSubmit()', '', '', $returnTrue, false, "\t\t");
                $right    .= $xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 16, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalView';
                break;
            case 0:
            default:
                break;
        }
        $functions      = $comment;
        $globalContent  = $xc->getXcGetGlobal(['xoopsUser', 'xoopsModule'], "\t\t");
        $globalContent  .= $xc->getXcEqualsOperator('$currentuid', '0', null, "\t\t");

        $contIf         = $pc->getPhpCodeConditions("\$xoopsUser->isAdmin(\$xoopsModule->mid())", '', '', "\t" . $returnTrue, false, "\t\t\t");
        $contIf         .= $xc->getXcEqualsOperator('$currentuid', '$xoopsUser->uid()', null, "\t\t\t");
        $globalContent  .= $pc->getPhpCodeConditions('isset($xoopsUser)', ' && ', 'is_object($xoopsUser)', $contIf, false, "\t\t");
        $globalContent  .= $xc->getXcXoopsHandler('groupperm', "\t\t");
        $globalContent  .= $xc->getXcEqualsOperator('$mid', '$xoopsModule->mid()', null, "\t\t");
        $globalContent  .= $xc->getXcXoopsHandler('member', "\t\t");

        $contIfInt      = $xc->getXcEqualsOperator('$my_group_ids', '[XOOPS_GROUP_ANONYMOUS]', null, "\t\t\t");
        $contElseInt    = $xc->getXcEqualsOperator('$my_group_ids', '$memberHandler->getGroupsByUser($currentuid);', null, "\t\t\t");
        $globalContent  .= $pc->getPhpCodeConditions('0', ' == ', '$currentuid', $contIfInt, $contElseInt, "\t\t");
        $globalContent  .= $cond;
        $globalContent  .= $this->getSimpleString("return false;", "\t\t");
        $functions      .= $pc->getPhpCodeFunction($funcname, '', $globalContent, 'public ', false, "\t");

        return $functions;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function renderClass()
    {
        $pc             = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc             = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content        = $this->getHeaderFilesComments($module, null, $namespace);
        $content        .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content        .= $pc->getPhpCodeDefined();
        $content        .= $pc->getPhpCodeCommentMultiLine(['Class Object' => $this->className]);
        $cCl            = $pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr         = '';
        $cCl            .= $pc->getPhpCodeFunction('__construct', '', $constr, 'public ', false, "\t");
        $arrGetInstance = ['@static function' => '&getInstance', '' => '', '@param' => 'null'];
        $cCl            .= $pc->getPhpCodeCommentMultiLine($arrGetInstance, "\t");
        $getInstance    = $pc->getPhpCodeVariableClass('static', 'instance', 'false', "\t\t");
        $instance       = $xc->getXcEqualsOperator('$instance', 'new self()', null, "\t\t\t");
        $getInstance    .= $pc->getPhpCodeConditions('!$instance', '', '', $instance, false, "\t\t");
        $cCl            .= $pc->getPhpCodeFunction('getInstance', '', $getInstance, 'public static ', false, "\t");
        $content        .= $pc->getPhpCodeClass($this->className, $cCl, '\XoopsObject');

        $this->create($moduleDirname, 'class', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);



        return $this->renderFile();
    }

    /**
     * @public function renderPermissionsHandler
     * @param null
     *
     * @return bool|string
     */
    public function renderPermissionsHandler()
    {
        $pc             = Modulebuilder\Files\CreatePhpCode::getInstance();
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content        = $this->getHeaderFilesComments($module, null, $namespace);
        $content        .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content        .= $pc->getPhpCodeDefined();
        $content        .= $pc->getPhpCodeCommentMultiLine(['Class Object' => $this->className]);

        $constr         = $pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr         .= $pc->getPhpCodeFunction('__construct', '', '', 'public ', false, "\t");
        $functions      = $constr;
        $functions      .= $this->getGlobalPerms(4);
        $functions      .= $this->getGlobalPerms(8);
        $functions      .= $this->getGlobalPerms(16);

        $content        .= $pc->getPhpCodeClass($this->className, $functions, '\XoopsPersistableObjectHandler');
        $this->create($moduleDirname, 'class', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
     * @public function renderConstants
     * @param null
     *
     * @return bool|string
     */
    public function renderConstants()
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();

        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $tables           = $this->getTables();
        $tablePermissions = [];
        foreach (array_keys($tables) as $t) {
            $tablePermissions[]   = $tables[$t]->getVar('table_permissions');
        }
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $contentFile    = $this->getHeaderFilesComments($module, null, $namespace);
        $contentFile    .= $pc->getPhpCodeDefined();
        $contentFile    .= $pc->getPhpCodeCommentMultiLine(['Class ' => $this->className]);

        $contentClass   = $pc->getPhpCodeBlankLine();
        $contentClass .= $pc->getPhpCodeCommentLine('Constants for status', '', "\t");
        $contentClass .= $pc->getPhpCodeConstant("STATUS_NONE     ", 0, "\t");
        $contentClass .= $pc->getPhpCodeConstant("STATUS_OFFLINE  ", 1, "\t");
        $contentClass .= $pc->getPhpCodeConstant("STATUS_SUBMITTED", 2, "\t");
        $contentClass .= $pc->getPhpCodeConstant("STATUS_APPROVED ", 3, "\t");
        $contentClass .= $pc->getPhpCodeConstant("STATUS_BROKEN   ", 4, "\t");


        if (in_array(1, $tablePermissions)) {
            $constPerm = $pc->getPhpCodeBlankLine();
            $constPerm .= $pc->getPhpCodeCommentLine('Constants for permissions', '', "\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_NONE   ", 0, "\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_VIEW   ", 1, "\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_SUBMIT ", 2, "\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_APPROVE", 3, "\t");
            $contentClass .= $constPerm;
        }
        $contentClass        .= $pc->getPhpCodeBlankLine();
        $contentFile   .= $pc->getPhpCodeClass($this->className, $contentClass);

        $this->create($moduleDirname, 'class', $filename, $contentFile, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    public function renderConstantsTestInterface()
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();

        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $tables           = $this->getTables();
        $tablePermissions = [];
        foreach (array_keys($tables) as $t) {
            $tablePermissions[]   = $tables[$t]->getVar('table_permissions');
        }
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $contentFile    = $this->getHeaderFilesComments($module, null, $namespace);
        $contentFile        .= $pc->getPhpCodeDefined();
        $contentFile        .= $pc->getPhpCodeCommentMultiLine(['Interface ' => $this->className]);
        $contentClass = '';
        if (in_array(1, $tablePermissions)) {
            $constPerm = $pc->getPhpCodeBlankLine();
            $constPerm .= $pc->getPhpCodeCommentLine('Constants for permissions', '', "\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_NONE   ", 0, 'protected static',"\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_VIEW   ", 1, 'protected static',"\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_SUBMIT ", 2, 'protected static',"\t");
            $constPerm .= $pc->getPhpCodeConstant("PERM_GLOBAL_APPROVE", 3, 'protected static',"\t");
            $contentClass .= $constPerm;
        }
        $contentClass        .= $pc->getPhpCodeBlankLine();
        $func = $pc->getPhpCodeCommentLine('trigger a warning if invalid "constant" requested', '', "\t\t");
        $if  = $pc->getPhpCodeTriggerError("\"Invalid Constant requested ('{\$val}')\"", 'E_USER_WARNING', "\t\t\t");
        $if  .= $this->getSimpleString("return false;", "\t\t\t");
        $func     .= $pc->getPhpCodeConditions('!isset($$val)', null, null, $if, false, "\t\t");
        $func  .= $this->getSimpleString('return self::$$val;', "\t");
        $contentClass         .= $pc->getPhpCodeFunction('getConstant', '$val', $func, 'final public static ', false, "\t");

        $contentFile   .= $pc->getPhpCodeInterface($this->className, $contentClass);

        $this->create($moduleDirname, 'class', $filename, $contentFile, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

}

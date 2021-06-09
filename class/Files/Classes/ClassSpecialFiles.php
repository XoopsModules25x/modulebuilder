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
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
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
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function renderClass()
    {
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $this->pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content        = $this->getHeaderFilesComments($module, null, $namespace);
        $content        .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content        .= $this->pc->getPhpCodeDefined();
        $content        .= $this->pc->getPhpCodeCommentMultiLine(['Class Object' => $this->className]);
        $cCl            = $this->pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr         = '';
        $cCl            .= $this->pc->getPhpCodeFunction('__construct', '', $constr, 'public ', false, "\t");
        $arrGetInstance = ['@static function' => '&getInstance', '' => '', '@param' => 'null'];
        $cCl            .= $this->pc->getPhpCodeCommentMultiLine($arrGetInstance, "\t");
        $getInstance    = $this->pc->getPhpCodeVariableClass('static', 'instance', 'false', "\t\t");
        $instance       = $this->xc->getXcEqualsOperator('$instance', 'new self()', null, "\t\t\t");
        $getInstance    .= $this->pc->getPhpCodeConditions('!$instance', '', '', $instance, false, "\t\t");
        $cCl            .= $this->pc->getPhpCodeFunction('getInstance', '', $getInstance, 'public static ', false, "\t");
        $content        .= $this->pc->getPhpCodeClass($this->className, $cCl, '\XoopsObject');

        $this->create($moduleDirname, 'class', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
     * @public function getGlobalPerms
     * @param null
     *
     * @return bool|string
     */
    public function getGlobalPerms($permId)
    {
        $module         = $this->getModule();
        $moduleDirname  = $module->getVar('mod_dirname');

        $returnTrue     = $this->getSimpleString("return true;", "\t\t\t");
        $right     = '';
        $cond      = '';
        $funcname  = '';
        $comment   = '';
        switch ($permId) {
            case 4:
                $comment  .= $this->pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalApprove', 'returns' => 'right for global approve', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $right    .= $this->xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 4, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $this->pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalApprove';
                break;
            case 8:
                $comment  .= $this->pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalSubmit', 'returns' => 'right for global submit', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $cond     .= $this->pc->getPhpCodeConditions('$this->getPermGlobalApprove()', '', '', $returnTrue, false, "\t\t");
                $right    .= $this->xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 8, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $this->pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalSubmit';
                break;
            case 16:
                $comment  .= $this->pc->getPhpCodeCommentMultiLine(['@public' => 'function permGlobalView', 'returns' => 'right for global view', '' => '', '@param' => 'null', '@return' => 'bool'], "\t");
                $cond     .= $this->pc->getPhpCodeConditions('$this->getPermGlobalApprove()', '', '', $returnTrue, false, "\t\t");
                $cond     .= $this->pc->getPhpCodeConditions('$this->getPermGlobalSubmit()', '', '', $returnTrue, false, "\t\t");
                $right    .= $this->xc->getXcCheckRight('$grouppermHandler', $moduleDirname . '_ac', 16, '$my_group_ids', '$mid', true, "\t\t\t");
                $cond     .= $this->pc->getPhpCodeConditions($right, '', '', $returnTrue, false, "\t\t");
                $funcname .= 'getPermGlobalView';
                break;
            case 0:
            default:
                break;
        }
        $functions      = $comment;
        $globalContent  = $this->xc->getXcGetGlobal(['xoopsUser', 'xoopsModule'], "\t\t");
        $globalContent  .= $this->xc->getXcEqualsOperator('$currentuid', '0', null, "\t\t");

        $contIf         = $this->pc->getPhpCodeConditions("\$xoopsUser->isAdmin(\$xoopsModule->mid())", '', '', "\t" . $returnTrue, false, "\t\t\t");
        $contIf         .= $this->xc->getXcEqualsOperator('$currentuid', '$xoopsUser->uid()', null, "\t\t\t");
        $globalContent  .= $this->pc->getPhpCodeConditions('isset($xoopsUser)', ' && ', '\is_object($xoopsUser)', $contIf, false, "\t\t");
        $globalContent  .= $this->xc->getXcXoopsHandler('groupperm', "\t\t");
        $globalContent  .= $this->xc->getXcEqualsOperator('$mid', '$xoopsModule->mid()', null, "\t\t");
        $globalContent  .= $this->xc->getXcXoopsHandler('member', "\t\t");

        $contIfInt      = $this->xc->getXcEqualsOperator('$my_group_ids', '[\XOOPS_GROUP_ANONYMOUS]', null, "\t\t\t");
        $contElseInt    = $this->xc->getXcEqualsOperator('$my_group_ids', '$memberHandler->getGroupsByUser($currentuid)', null, "\t\t\t");
        $globalContent  .= $this->pc->getPhpCodeConditions('$currentuid', ' == ', '0', $contIfInt, $contElseInt, "\t\t");
        $globalContent  .= $cond;
        $globalContent  .= $this->getSimpleString("return false;", "\t\t");
        $functions      .= $this->pc->getPhpCodeFunction($funcname, '', $globalContent, 'public ', false, "\t");

        return $functions;
    }

    /**
     * @public function renderPermissionsHandler
     * @param null
     *
     * @return bool|string
     */
    public function renderPermissionsHandler()
    {
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $this->pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content        = $this->getHeaderFilesComments($module, null, $namespace);
        $content        .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content        .= $this->pc->getPhpCodeDefined();
        $content        .= $this->pc->getPhpCodeCommentMultiLine(['Class Object' => $this->className]);

        $constr         = $this->pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr         .= $this->pc->getPhpCodeFunction('__construct', '', '', 'public ', false, "\t");
        $functions      = $constr;
        $functions      .= $this->getGlobalPerms(4);
        $functions      .= $this->getGlobalPerms(8);
        $functions      .= $this->getGlobalPerms(16);

        $content        .= $this->pc->getPhpCodeClass($this->className, $functions, '\XoopsPersistableObjectHandler');
        $this->create($moduleDirname, 'class', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
     * @public function renderConstantsInterface
     * @param null
     *
     * @return bool|string
     */
    public function renderConstantsInterface()
    {
        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $tables           = $this->getTables();
        $tablePermissions = [];
        $tableRate        = [];
        foreach (\array_keys($tables) as $t) {
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
            $tableRate[]        = $tables[$t]->getVar('table_rate');
        }
        $moduleDirname  = $module->getVar('mod_dirname');
        $namespace      = $this->pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $contentFile    = $this->getHeaderFilesComments($module, null, $namespace);
        $contentFile    .= $this->pc->getPhpCodeCommentMultiLine(['Interface ' => $this->className]);

        $contentClass   = $this->pc->getPhpCodeBlankLine();
        $contentClass .= $this->pc->getPhpCodeCommentLine('Constants for tables', '', "\t");
        foreach (\array_keys($tables) as $t) {
            $tablePermissions[]   = $tables[$t]->getVar('table_permissions');
            $stuTableName = \mb_strtoupper($tables[$t]->getVar('table_name'));
            $contentClass .= $this->pc->getPhpCodeConstant("TABLE_" . $stuTableName, $t, "\t",'public const');
        }

        $contentClass .= $this->pc->getPhpCodeBlankLine();
        $contentClass .= $this->pc->getPhpCodeCommentLine('Constants for status', '', "\t");
        $contentClass .= $this->pc->getPhpCodeConstant("STATUS_NONE     ", 0, "\t",'public const');
        $contentClass .= $this->pc->getPhpCodeConstant("STATUS_OFFLINE  ", 1, "\t",'public const');
        $contentClass .= $this->pc->getPhpCodeConstant("STATUS_SUBMITTED", 2, "\t",'public const');
        $contentClass .= $this->pc->getPhpCodeConstant("STATUS_APPROVED ", 3, "\t",'public const');
        $contentClass .= $this->pc->getPhpCodeConstant("STATUS_BROKEN   ", 4, "\t",'public const');
        if (\in_array(1, $tablePermissions)) {
            $constPerm = $this->pc->getPhpCodeBlankLine();
            $constPerm .= $this->pc->getPhpCodeCommentLine('Constants for permissions', '', "\t");
            $constPerm .= $this->pc->getPhpCodeConstant("PERM_GLOBAL_NONE   ", 0, "\t",'public const');
            $constPerm .= $this->pc->getPhpCodeConstant("PERM_GLOBAL_VIEW   ", 1, "\t", 'public const');
            $constPerm .= $this->pc->getPhpCodeConstant("PERM_GLOBAL_SUBMIT ", 2, "\t", 'public const');
            $constPerm .= $this->pc->getPhpCodeConstant("PERM_GLOBAL_APPROVE", 3, "\t", 'public const');
            $contentClass .= $constPerm;
        }
        if (\in_array(1, $tableRate)) {
            $constRate = $this->pc->getPhpCodeBlankLine();
            $constRate .= $this->pc->getPhpCodeCommentLine('Constants for rating', '', "\t");
            $constRate .= $this->pc->getPhpCodeConstant("RATING_NONE    ", 0, "\t",'public const');
            $constRate .= $this->pc->getPhpCodeConstant("RATING_5STARS  ", 1, "\t", 'public const');
            $constRate .= $this->pc->getPhpCodeConstant("RATING_10STARS ", 2, "\t", 'public const');
            $constRate .= $this->pc->getPhpCodeConstant("RATING_LIKES   ", 3, "\t", 'public const');
            $constRate .= $this->pc->getPhpCodeConstant("RATING_10NUM   ", 4, "\t", 'public const');
            $contentClass .= $constRate;
        }
        $contentClass        .= $this->pc->getPhpCodeBlankLine();

        $contentFile   .= $this->pc->getPhpCodeInterface($this->className, $contentClass);

        $this->create($moduleDirname, 'class', $filename, $contentFile, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

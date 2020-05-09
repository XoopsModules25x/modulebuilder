<?php

namespace XoopsModules\Modulebuilder\Files\Includes;

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
 * Class IncludeCommentFunctions.
 */
class IncludeCommentFunctions extends Files\CreateFile
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
     * @return IncludeCommentFunctions
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
     * @param        $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getCommentBody
     * @param string $module
     * @param mixed $table
     * @return string
     */
    public function getCommentBody($module, $table)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName     = ucfirst($tableName);

        $ret    = $pc->getPhpCodeCommentMultiLine(['CommentsUpdate' => '', '' => '', '@param mixed  $itemId' => '', '@param mixed  $itemNumb' => '', '@return' => 'bool']);
        $func1  = $xc->getXcEqualsOperator('$itemId', '(int)$itemId', '', "\t");
        $func1  .= $xc->getXcEqualsOperator('$itemNumb', '(int)$itemNumb', '', "\t");
        $func1  .= $xc->getXcEqualsOperator('$article', "new {$ucfModuleDirname}{$ucfTableName}(\$itemId)", '', "\t");
        $contIf = $this->getSimpleString('return false;',"\t\t");
        $func1  .= $pc->getPhpCodeConditions('!$article->updateComments($itemNumb)','','',$contIf, false,"\t");
        $func1  .= $this->getSimpleString('return true;',"\t");
        $ret    .= $pc->getPhpCodeFunction($moduleDirname . 'CommentsUpdate', '$itemId, $itemNumb', $func1);
        $ret    .= $pc->getPhpCodeCommentMultiLine(['CommentsApprove' => '', '' => '', '@param mixed' => '$comment', '@return' => 'bool']);
        $func2  = $pc->getPhpCodeCommentLine('notification mail here','',"\t");
        $func2  .= $pc->getPhpCodeBlankLine();
        $func2  .= $this->getSimpleString('return false;',"\t");
        $func2  .= $pc->getPhpCodeBlankLine();
        $ret    .= $pc->getPhpCodeFunction($moduleDirname . 'CommentsApprove', '&$comment', $func2);

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
        $moduleDirname = $module->getVar('mod_dirname');

        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getCommentBody($module, $table);

        $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

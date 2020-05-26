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
     * @var string
     */
    private $xc = null;

    /**
     * @var string
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
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $tableFieldName   = $table->getVar('table_fieldname');
        $fieldId          = '';
        $ccFieldId        = '';
        $ccFieldMain      = '';
        $fieldMain        = '';
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId   = $fieldName;
                $ccFieldId = $this->getCamelCase($fieldId, false, true);
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain    = $fieldName; // fieldMain = fields parameters main field
                $ccFieldMain  = $this->getCamelCase($fieldMain, false, true);
            }
        }

        $t      = "\t";
        $ret    = $this->pc->getPhpCodeCommentMultiLine(['CommentsUpdate' => '', '' => '', '@param mixed  $itemId' => '', '@param mixed  $itemNumb' => '', '@return' => 'bool']);
        $func1  = $this->xc->getXcHelperGetInstance($moduleDirname, $t);
        $func1  .= $this->xc->getXcHandlerLine($tableName, $t);
        $func1  .= $this->xc->getXcEqualsOperator("\${$ccFieldId}", '(int)$itemId', '', $t);
        $func1  .= $this->xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $func1  .= $this->xc->getXcSetVarObj($tableName, $tableFieldName . '_comments', '(int)$itemNumb', $t);
        $insert = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $contIf = $this->getSimpleString('return true;',$t . "\t");
        $func1  .= $this->pc->getPhpCodeConditions($insert, '', '', $contIf, false, $t);
        $func1  .= $this->getSimpleString('return false;',$t);
        $ret    .= $this->pc->getPhpCodeFunction($moduleDirname . 'CommentsUpdate', '$itemId, $itemNumb', $func1);
        $ret    .= $this->pc->getPhpCodeCommentMultiLine(['CommentsApprove' => '', '' => '', '@param mixed' => '$comment', '@return' => 'bool']);

        $func2 = $this->pc->getPhpCodeCommentLine('Notification event','',$t);
        $func2 .= $this->xc->getXcHelperGetInstance($moduleDirname, $t);
        $func2 .= $this->xc->getXcHandlerLine($tableName, $t);
        $func2 .= $this->xc->getXcGetVar($ccFieldId, "comment", "com_itemid", false, $t);
        $func2 .= $this->xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $func2 .= $this->xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t);
        $func2 .= $this->pc->getPhpCodeBlankLine();
        $func2 .= $this->pc->getPhpCodeArray('tags', [], false, $t);
        $func2 .= $this->xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t);
        $url    = "XOOPS_URL . '/modules/{$moduleDirname}/{$tableName}.php?op=show&{$fieldId}=' . \${$ccFieldId}";
        $func2 .= $this->xc->getXcEqualsOperator("\$tags['ITEM_URL'] ", $url, '', $t);
        $func2 .= $this->xc->getXcXoopsHandler('notification', $t);
        $func2 .= $this->pc->getPhpCodeCommentLine('Event modify notification', null, $t);
        $func2 .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_comment', \$tags);", $t);
        $func2 .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \${$ccFieldId}, '{$tableSoleName}_comment', \$tags);", $t);
        $func2 .= $this->getSimpleString('return true;',$t);
        $func2 .= $this->pc->getPhpCodeBlankLine();
        $ret   .= $this->pc->getPhpCodeFunction($moduleDirname . 'CommentsApprove', '&$comment', $func2);

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

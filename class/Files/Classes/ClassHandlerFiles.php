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
 * Class ClassHandlerFiles.
 */
class ClassHandlerFiles extends Files\CreateFile
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
    private $helper = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc     = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->helper = Modulebuilder\Helper::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @return bool|ClassHandlerFiles
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
     * @public function getClassHandler
     *
     * @param string $moduleDirname
     * @param string $table
     * @param string $fieldId
     * @param        $fieldName
     * @param string $fieldMain
     * @param        $fieldParentId
     * @param        $fieldElement
     * @return string
     */
    private function getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParentId, $fieldElement)
    {
        $tableName        = $table->getVar('table_name');
        $tableFieldName   = $table->getVar('table_fieldname');
        $ucfTableName     = ucfirst($tableName);
        $multiLineCom     = ['Class Object Handler' => $ucfTableName];
        $ret              = $this->pc->getPhpCodeCommentMultiLine($multiLineCom);

        $cClh   = $this->pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => '\XoopsDatabase $db'], "\t");
        $constr = "\t\tparent::__construct(\$db, '{$moduleDirname}_{$tableName}', {$ucfTableName}::class, '{$fieldId}', '{$fieldMain}');\n";

        $cClh .= $this->pc->getPhpCodeFunction('__construct', '\XoopsDatabase $db', $constr, 'public ', false, "\t");
        $cClh .= $this->getClassCreate();
        $cClh .= $this->getClassGet();
        $cClh .= $this->getClassGetInsertId();
        $cClh .= $this->getClassCounter($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassAll($tableName, $fieldId, $fieldMain);
        $cClh .= $this->getClassCriteria($tableName);
        if ($fieldElement > 16 && in_array(1, $fieldParentId)) {
            $cClh .= $this->getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldElement);
            $cClh .= $this->getClassGetTableSolenameById($table, $fieldMain);
        }

        $ret .= $this->pc->getPhpCodeClass("{$ucfTableName}Handler", $cClh, '\XoopsPersistableObjectHandler');

        return $ret;
    }

    /**
     * @public function getClassCreate
     *
     * @return string
     */
    private function getClassCreate()
    {
        $ret   = $this->pc->getPhpCodeCommentMultiLine(['@param bool' => '$isNew', '' => '', '@return' => 'object'], "\t");
        $cClhc = $this->getSimpleString('return parent::create($isNew);', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('create', '$isNew = true', $cClhc, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassGet
     *
     * @return string
     */
    private function getClassGet()
    {
        $ret   = $this->pc->getPhpCodeCommentMultiLine(['retrieve a' => 'field', '' => '', '@param int' => '$i field id', '@param null' => 'fields', '@return mixed reference to the' => '{@link Get} object'], "\t");
        $cClhg = $this->getSimpleString('return parent::get($i, $fields);', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('get', '$i = null, $fields = null', $cClhg, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassGetInsertId
     *
     * @return string
     */
    private function getClassGetInsertId()
    {
        $ret     = $this->pc->getPhpCodeCommentMultiLine(['get inserted' => 'id', '' => '', '@param' => 'null', '@return integer reference to the' => '{@link Get} object'], "\t");
        $cClhgid = $this->getSimpleString('return $this->db->getInsertId();', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getInsertId', '', $cClhgid, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassCounter
     *
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     *
     * @return string
     */
    private function getClassCounter($tableName, $fieldId, $fieldMain)
    {
        $ucfTableName = ucfirst($tableName);
        $ret          = $this->pc->getPhpCodeCommentMultiLine(['Get Count ' . $ucfTableName => 'in the database', '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'int'], "\t");

        $critCount  = $this->xc->getXcCriteriaCompo('crCount' . $ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$crCount{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critCount  .= $this->xc->getXcEqualsOperator('$crCount' . $ucfTableName, $paramsCrit, null, "\t\t");
        $critCount  .= $this->getSimpleString("return parent::getCount(\$crCount{$ucfTableName});", "\t\t");
        $params     = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction('getCount' . $ucfTableName, $params, $critCount, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassAll
     *
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     *
     * @return string
     */
    private function getClassAll($tableName, $fieldId, $fieldMain)
    {
        $ucfTableName = ucfirst($tableName);
        $ret          = $this->pc->getPhpCodeCommentMultiLine(['Get All ' . $ucfTableName => 'in the database', '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'array'], "\t");

        $critAll    = $this->xc->getXcCriteriaCompo('crAll' . $ucfTableName, "\t\t");
        $paramsCrit = "\$this->get{$ucfTableName}Criteria(\$crAll{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critAll    .= $this->xc->getXcEqualsOperator('$crAll' . $ucfTableName, $paramsCrit, null, "\t\t");
        $critAll    .= $this->getSimpleString("return parent::getAll(\$crAll{$ucfTableName});", "\t\t");
        $params     = "\$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction('getAll' . $ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassByCategory
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableFieldName
     * @param $fieldId
     * @param $fieldName
     * @param $fieldMain
     * @param $fieldElement
     * @return string
     */
    private function getClassByCategory($moduleDirname, $tableName, $tableFieldName, $fieldId, $fieldName, $fieldMain, $fieldElement)
    {
        $ucfTableName      = ucfirst($tableName);
        $fieldElements     = $this->helper->getHandler('Fieldelements')->get($fieldElement);
        $fieldElementName  = $fieldElements->getVar('fieldelement_name');
        $fieldNameDesc     = ucfirst(mb_substr($fieldElementName, mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName)));
        $topicTableName    = str_replace(': ', '', $fieldNameDesc);
        $lcfTopicTableName = lcfirst($topicTableName);

        $ret = $this->pc->getPhpCodeCommentMultiLine(["Get All {$ucfTableName} By" => "{$fieldNameDesc} Id", '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'array'], "\t");

        $critAll = $this->xc->getXcXoopsHandler('groupperm', "\t\t");
        $param1  = "'{$moduleDirname}_view'";
        $param2  = "\$GLOBALS['xoopsUser']->getGroups()";
        $param3  = "\$GLOBALS['xoopsModule']->getVar('mid')";
        $critAll .= $this->xc->getXcGetItemIds($lcfTopicTableName, 'grouppermHandler', $param1, $param2, $param3, "\t\t");
        $critAll .= $this->xc->getXcCriteriaCompo('crAll' . $ucfTableName, "\t\t");

        if (false !== mb_strpos($fieldName, 'status')) {
            $crit    = $this->xc->getXcCriteria('', "'{$fieldName}'", '0', "'!='", true);
            $critAll .= $this->xc->getXcCriteriaAdd('crAll' . $ucfTableName, $crit, "\t\t");
        }
        $paramsCritAll = "\$this->get{$ucfTableName}Criteria(\$crAll{$ucfTableName}, \$start, \$limit, \$sort, \$order)";
        $critAll       .= $this->xc->getXcEqualsOperator('$crAll' . $ucfTableName, $paramsCritAll, null, "\t\t");

        $critAll .= $this->getSimpleString("return parent::getAll(\$crAll{$ucfTableName});", "\t\t");
        $params  = "\${$tableFieldName}Id, \$start = 0, \$limit = 0, \$sort = '{$fieldId} ASC, {$fieldMain}', \$order = 'ASC'";

        $ret .= $this->pc->getPhpCodeFunction("getAll{$ucfTableName}By{$fieldNameDesc}Id" . $ucfTableName, $params, $critAll, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassCriteria
     *
     * @param $tableName
     * @return string
     */
    private function getClassCriteria($tableName)
    {
        $ucfTableName = ucfirst($tableName);
        $ret          = $this->pc->getPhpCodeCommentMultiLine(['Get' => 'Criteria ' . $ucfTableName, '@param       ' => "\$cr{$ucfTableName}", '@param int    $start' => '', '@param int    $limit' => '', '@param string $sort' => '', '@param string $order' => '', '@return' => 'int'], "\t");

        $paramsAllCriteria = "\$cr{$ucfTableName}, \$start, \$limit, \$sort, \$order";

        $critSets = $this->xc->getXcCriteriaSetStart('cr' . $ucfTableName, '$start', "\t\t");
        $critSets .= $this->xc->getXcCriteriaSetLimit('cr' . $ucfTableName, '$limit', "\t\t");
        $critSets .= $this->xc->getXcCriteriaSetSort('cr' . $ucfTableName, '$sort', "\t\t");
        $critSets .= $this->xc->getXcCriteriaSetOrder('cr' . $ucfTableName, '$order', "\t\t");
        $critSets .= $this->getSimpleString("return \$cr{$ucfTableName};", "\t\t");

        $ret .= $this->pc->getPhpCodeFunction("get{$ucfTableName}Criteria", $paramsAllCriteria, $critSets, 'private ', false, "\t");

        return $ret;
    }

    /**
     * @public function getClassGetTableSolenameById
     *
     * @param $table
     * @param $fieldMain
     * @return string
     */
    private function getClassGetTableSolenameById($table, $fieldMain)
    {
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $ucfTableSoleName = ucfirst($tableSoleName);
        $ccTableSoleName  = $this->getCamelCase($tableSoleName, true);

        $ret              = $this->pc->getPhpCodeCommentMultiLine(['Returns the' => $ucfTableSoleName . ' from id', '' => '', '@return' => 'string'], "\t");
        $soleName         = $this->xc->getXcEqualsOperator("\${$tableSoleName}Id", "(int)( \${$tableSoleName}Id )", null, "\t\t");
        $soleName         .= $this->xc->getXcEqualsOperator("\${$tableSoleName}", "''", null, "\t\t");
        $contentIf        = $this->xc->getXcHandlerLine($tableName, "\t\t\t");
        $contentIf        .= $this->xc->getXcHandlerGet($tableName, "\${$tableSoleName}Id", 'Obj', true, false, "\t\t\t");
        $getVar           = $this->xc->getXcGetVar($ccTableSoleName, "{$tableSoleName}Obj", $fieldMain, false, "\t\t\t\t");
        $contentIf        .= $this->pc->getPhpCodeConditions("is_object( \${$tableSoleName}Obj )", '', '', $getVar, false, "\t\t\t");
        $soleName         .= $this->pc->getPhpCodeConditions("\${$tableSoleName}Id", ' > ', '0', $contentIf, false, "\t\t");
        $soleName         .= $this->getSimpleString("return \${$tableSoleName};", "\t\t");

        $ret .= $this->pc->getPhpCodeFunction("get{$ucfTableSoleName}FromId", "\${$tableSoleName}Id", $soleName, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $fields         = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm    = [];
        $fieldParentId  = [];
        $fieldElementId = [];
        $fieldId        = null;
        $fieldName      = null;
        $fieldMain      = null;
        $fieldElement   = null;
        foreach (array_keys($fields) as $f) {
            $fieldName       = $fields[$f]->getVar('field_name');
            $fieldInForm[]   = $fields[$f]->getVar('field_inform');
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName; // $fieldId = fields parameter index field
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // $fieldMain = fields parameter main field
            }
            $fieldElement = $fields[$f]->getVar('field_element');

            $fieldElements    = $this->helper->getHandler('Fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
        }
        $namespace = $this->pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content   = $this->getHeaderFilesComments($module, null, $namespace);
        $content   .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content   .= $this->getClassObjectHandler($moduleDirname, $table, $fieldId, $fieldName, $fieldMain, $fieldParentId, $fieldElement);

        $this->create($moduleDirname, 'class', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

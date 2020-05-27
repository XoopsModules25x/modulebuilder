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
 * Class IncludeSearch.
 */
class IncludeSearch extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $cf = null;

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
        $this->cf = Modulebuilder\Files\CreateFile::getInstance();
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return IncludeSearch
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
     * @param mixed  $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        $this->setTables($tables);
    }

    /**
     * @static function getSearchFunction
     * @param $moduleDirname
     *
     * @return string
     */
    public function getSearchFunction($moduleDirname)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $tables           = $this->getTables();
        $t     = "\t";
        $ret   = $this->pc->getPhpCodeCommentMultiLine(['search callback functions' => '', '' => '', '@param $queryarray' => '', '@param $andor' => '', '@param $limit' => '', '@param $offset' => '', '@param $userid' => '', '@return' => 'mixed $itemIds']);
        $func  = $this->xc->getXcEqualsOperator('$ret', "[]", '', $t);
        $func .= $this->xc->getXcGetInstance('helper', "\XoopsModules\\{$ucfModuleDirname}\Helper", $t);

        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                if(1 === (int) $tables[$i]->getVar('table_search')) {
                    $tableId        = $tables[$i]->getVar('table_id');
                    $tableMid       = $tables[$i]->getVar('table_mid');
                    $tableName      = $tables[$i]->getVar('table_name');
                    $tableFieldname = $tables[$i]->getVar('table_fieldname');
                    $func   .= $this->pc->getPhpCodeCommentLine('search in table', $tableName, $t);
                    $func   .= $this->pc->getPhpCodeCommentLine('search keywords', '', $t);
                    $func   .= $this->xc->getXcEqualsOperator('$elementCount', '0', '', $t);
                    $func   .= $this->xc->getXcHandlerLine($tableName, $t);
                    $contIf = $this->xc->getXcEqualsOperator('$elementCount', 'count($queryarray)', '', $t . "\t");
                    $func   .= $this->pc->getPhpCodeConditions('is_array($queryarray)', '', '', $contIf, false, $t);
                    $contIf = $this->xc->getXcCriteriaCompo('crKeywords', $t . "\t");
                    $for    = $this->xc->getXcCriteriaCompo('crKeyword', $t . "\t\t");

                    $fields    = $this->getTableFields($tableMid, $tableId);
                    $fieldId   = '';
                    $fieldMain = '';
                    $fieldDate = '';
                    $countField = 0;
                    foreach (array_keys($fields) as $f) {
                        $fieldName = $fields[$f]->getVar('field_name');
                        if (0 == $f) {
                            $fieldId = $fieldName;
                        }
                        if (1 === (int)$fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        if (15 === (int)$fields[$f]->getVar('field_element') || 21 === (int)$fields[$f]->getVar('field_element')) {
                            $fieldDate = $fieldName;
                        }
                        if (1 === (int)$fields[$f]->getVar('field_search')) {
                            $crit = $this->xc->getXcCriteria('', "'{$fieldName}'", "'%' . \$queryarray[\$i] . '%'", "'LIKE'", true, $t . "\t");
                            $for  .= $this->xc->getXcCriteriaAdd('crKeyword', $crit, $t . "\t\t", "\n", "'OR'");
                            $countField++;
                        }
                    }
                    if ($countField > 0) {
                        $for .= $this->xc->getXcCriteriaAdd('crKeywords', '$crKeyword', $t . "\t\t", "\n", '$andor');
                    }
                    $for      .= $this->pc->getPhpCodeUnset('crKeyword', $t . "\t\t");
                    $contIf   .= $this->pc->getPhpCodeFor( 'i', $for, 'elementCount', '0', ' < ', $t . "\t");
                    $func     .= $this->pc->getPhpCodeConditions('$elementCount', ' > ', '0', $contIf, false, $t);
                    $func     .= $this->pc->getPhpCodeCommentLine('search user(s)', '', $t);
                    $contIf   = $this->xc->getXcEqualsOperator('$userid', "array_map('intval', \$userid)", '', $t . "\t");
                    $contIf   .= $this->xc->getXcCriteriaCompo('crUser', $t . "\t");
                    $crit     = $this->xc->getXcCriteria('', "'{$tableFieldname}_submitter'", "'(' . implode(',', \$userid) . ')'", "'IN'", true, $t . "\t");
                    $contIf   .= $this->xc->getXcCriteriaAdd('crUser', $crit, $t . "\t", "\n", "'OR'");
                    $contElse = $this->xc->getXcCriteriaCompo('crUser', $t . "\t");
                    $crit     = $this->xc->getXcCriteria('', "'{$tableFieldname}_submitter'", '$userid', '', true, $t . "\t");
                    $contElse .= $this->xc->getXcCriteriaAdd('crUser', $crit, $t . "\t", "\n", "'OR'");
                    $func     .= $this->pc->getPhpCodeConditions('$userid && is_array($userid)', '', '', $contIf, $contElse, $t, 'is_numeric($userid) && $userid > 0');
                    $func     .= $this->xc->getXcCriteriaCompo('crSearch', $t);
                    $contIf   = $this->xc->getXcCriteriaAdd('crSearch', '$crKeywords', $t . "\t", "\n", "'AND'");
                    $cond     = $this->pc->getPhpCodeIsset('crKeywords');
                    $func     .= $this->pc->getPhpCodeConditions($cond, '', '', $contIf, false, $t);
                    $contIf   = $this->xc->getXcCriteriaAdd('crSearch', '$crUser', $t . "\t", "\n", "'AND'");
                    $cond     = $this->pc->getPhpCodeIsset('crUser');
                    $func     .= $this->pc->getPhpCodeConditions($cond, '', '', $contIf, false, $t);
                    $func     .= $this->xc->getXcCriteriaSetStart( 'crSearch', '$offset', $t);
                    $func     .= $this->xc->getXcCriteriaSetLimit( 'crSearch', '$limit', $t);
                    if ('' !== $fieldDate) {
                        $func .= $this->xc->getXcCriteriaSetSort( 'crSearch', "'{$fieldDate}'", $t);
                    } else {
                        $func .= $this->xc->getXcCriteriaSetSort( 'crSearch', "'{$fieldId}_date'", $t);
                    }
                    $func .= $this->xc->getXcCriteriaSetOrder( 'crSearch', "'DESC'", $t);
                    $func .= $this->xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$crSearch', $t);
                    $contentForeach = $t . "\t\$ret[] = [\n";
                    $contentForeach .= $t . "\t\t'image'  => 'assets/icons/16/{$tableName}.png',\n";
                    $contentForeach .= $t . "\t\t'link'   => '{$tableName}.php?op=show&amp;{$fieldId}=' . \${$tableName}All[\$i]->getVar('{$fieldId}'),\n";
                    $contentForeach .= $t . "\t\t'title'  => \${$tableName}All[\$i]->getVar('{$fieldMain}'),\n";
                    if ('' !== $fieldDate) {
                        $contentForeach .= $t . "\t\t'time'   => \${$tableName}All[\$i]->getVar('{$fieldDate}')\n";
                    }
                    $contentForeach .= $t . "\t];\n";
                    $func .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t");
                    $func .= $this->pc->getPhpCodeUnset('crKeywords', $t);
                    $func .= $this->pc->getPhpCodeUnset('crKeyword', $t);
                    $func .= $this->pc->getPhpCodeUnset('crUser', $t);
                    $func .= $this->pc->getPhpCodeUnset('crSearch', $t);
                }
                $func .= $this->pc->getPhpCodeBlankLine();
            }
        }
        $func .= $this->getSimpleString('return $ret;', $t);
        $func .= $this->pc->getPhpCodeBlankLine();
        $ret  .= $this->pc->getPhpCodeFunction("{$moduleDirname}_search", '$queryarray, $andor, $limit, $offset, $userid', $func);

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
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content       .= $this->getSearchFunction($moduleDirname);

        $this->cf->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->cf->renderFile();
    }
}

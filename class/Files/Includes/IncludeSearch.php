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
    private $tdmcfile = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = Modulebuilder\Files\CreateFile::getInstance();
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
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ucfModuleDirname = ucfirst($moduleDirname);
        $tables           = $this->getTables();
        $t     = "\t";
        $ret   = $pc->getPhpCodeCommentMultiLine(['search callback functions' => '', '' => '', '@param $queryarray' => '', '@param $andor' => '', '@param $limit' => '', '@param $offset' => '', '@param $userid' => '', '@return' => 'mixed $itemIds']);
        $func  = $xc->getXcEqualsOperator('$ret', "[]", '', $t);
        $func .= $xc->getXcGetInstance('helper', "\XoopsModules\\{$ucfModuleDirname}\Helper", $t);

        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                if(1 === (int) $tables[$i]->getVar('table_search')) {
                    $tableId        = $tables[$i]->getVar('table_id');
                    $tableMid       = $tables[$i]->getVar('table_mid');
                    $tableName      = $tables[$i]->getVar('table_name');
                    $tableFieldname = $tables[$i]->getVar('table_fieldname');
                    $func   .= $pc->getPhpCodeCommentLine('search in table', $tableName, $t);
                    $func   .= $pc->getPhpCodeCommentLine('search keywords', '', $t);
                    $func   .= $xc->getXcEqualsOperator('$elementCount', '0', '', $t);
                    $func   .= $xc->getXcHandlerLine($tableName, $t);
                    $contIf = $xc->getXcEqualsOperator('$elementCount', 'count($queryarray)', '', $t . "\t");
                    $func   .= $pc->getPhpCodeConditions('is_array($queryarray)', '', '', $contIf, false, $t);
                    $contIf = $xc->getXcCriteriaCompo('crKeywords', $t . "\t");
                    $for    = $xc->getXcCriteriaCompo('crKeyword', $t . "\t\t");

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
                            $crit = $xc->getXcCriteria('', "'{$fieldName}'", "'%' . \$queryarray[\$i] . '%'", "'LIKE'", true, $t . "\t");
                            $for  .= $xc->getXcCriteriaAdd('crKeyword', $crit, $t . "\t\t", "\n", "'OR'");
                            $countField++;
                        }
                    }
                    if ($countField > 0) {
                        $for .= $xc->getXcCriteriaAdd('crKeywords', '$crKeyword', $t . "\t\t", "\n", '$andor');
                    }
                    $for      .= $pc->getPhpCodeUnset('crKeyword', $t . "\t\t");
                    $contIf   .= $pc->getPhpCodeFor( 'i', $for, 'elementCount', '0', ' < ', $t . "\t");
                    $func     .= $pc->getPhpCodeConditions('$elementCount', ' > ', '0', $contIf, false, $t);
                    $func     .= $pc->getPhpCodeCommentLine('search user(s)', '', $t);
                    $contIf   = $xc->getXcEqualsOperator('$userid', "array_map('intval', \$userid)", '', $t . "\t");
                    $contIf   .= $xc->getXcCriteriaCompo('crUser', $t . "\t");
                    $crit     = $xc->getXcCriteria('', "'{$tableFieldname}_submitter'", "'(' . implode(',', \$userid) . ')'", "'IN'", true, $t . "\t");
                    $contIf   .= $xc->getXcCriteriaAdd('crUser', $crit, $t . "\t", "\n", "'OR'");
                    $contElse = $xc->getXcCriteriaCompo('crUser', $t . "\t");
                    $crit     = $xc->getXcCriteria('', "'{$tableFieldname}_submitter'", '$userid', '', true, $t . "\t");
                    $contElse .= $xc->getXcCriteriaAdd('crUser', $crit, $t . "\t", "\n", "'OR'");
                    $func     .= $pc->getPhpCodeConditions('$userid && is_array($userid)', '', '', $contIf, $contElse, $t, 'is_numeric($userid) && $userid > 0');
                    $func     .= $xc->getXcCriteriaCompo('crSearch', $t);
                    $contIf   = $xc->getXcCriteriaAdd('crSearch', '$crKeywords', $t . "\t", "\n", "'AND'");
                    $cond     = $pc->getPhpCodeIsset('crKeywords');
                    $func     .= $pc->getPhpCodeConditions($cond, '', '', $contIf, false, $t);
                    $contIf   = $xc->getXcCriteriaAdd('crSearch', '$crUser', $t . "\t", "\n", "'AND'");
                    $cond     = $pc->getPhpCodeIsset('crUser');
                    $func     .= $pc->getPhpCodeConditions($cond, '', '', $contIf, false, $t);
                    $func     .= $xc->getXcCriteriaSetStart( 'crSearch', '$offset', $t);
                    $func     .= $xc->getXcCriteriaSetLimit( 'crSearch', '$limit', $t);
                    if ('' !== $fieldDate) {
                        $func .= $xc->getXcCriteriaSetSort( 'crSearch', "'{$fieldDate}'", $t);
                    } else {
                        $func .= $xc->getXcCriteriaSetSort( 'crSearch', "'{$fieldId}_date'", $t);
                    }
                    $func .= $xc->getXcCriteriaSetOrder( 'crSearch', "'DESC'", $t);
                    $func .= $xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$crSearch', $t);
                    $contentForeach = $t . "\t\$ret[] = [\n";
                    $contentForeach .= $t . "\t\t'image'  => 'assets/icons/16/{$tableName}.png',\n";
                    $contentForeach .= $t . "\t\t'link'   => '{$tableName}.php?op=show&amp;{$fieldId}=' . \${$tableName}All[\$i]->getVar('{$fieldId}'),\n";
                    $contentForeach .= $t . "\t\t'title'  => \${$tableName}All[\$i]->getVar('{$fieldMain}'),\n";
                    if ('' !== $fieldDate) {
                        $contentForeach .= $t . "\t\t'time'   => \${$tableName}All[\$i]->getVar('{$fieldDate}')\n";
                    }
                    $contentForeach .= $t . "\t];\n";
                    $func .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t");
                    $func .= $pc->getPhpCodeUnset('crKeywords', $t);
                    $func .= $pc->getPhpCodeUnset('crKeyword', $t);
                    $func .= $pc->getPhpCodeUnset('crUser', $t);
                    $func .= $pc->getPhpCodeUnset('crSearch', $t);
                }
                $func .= $pc->getPhpCodeBlankLine();
            }
        }
        $func .= $this->getSimpleString('return $ret;', $t);
        $func .= $pc->getPhpCodeBlankLine();
        $ret  .= $pc->getPhpCodeFunction("{$moduleDirname}_search", '$queryarray, $andor, $limit, $offset, $userid', $func);

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content       .= $this->getSearchFunction($moduleDirname);

        $this->tdmcfile->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

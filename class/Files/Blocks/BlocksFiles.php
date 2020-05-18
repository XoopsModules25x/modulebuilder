<?php

namespace XoopsModules\Modulebuilder\Files\Blocks;

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
 * Class BlocksFiles.
 */
class BlocksFiles extends Files\CreateFile
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
     * @return BlocksFiles
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
     * @private  function getBlocksShow
     * @param     $moduleDirname
     * @param     $tableName
     * @param     $tableFieldname
     * @param     $fields
     * @param     $fieldId
     * @param int $fieldParent
     * @return string
     */
    private function getBlocksShow($moduleDirname, $tableName, $tableFieldname, $fields, $fieldId, $fieldParent = 0)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfTableName     = ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;

        $ret  = $pc->getPhpCodeCommentMultiLine(['Function' => 'show block', '@param  $options' => '', '@return' => 'array']);

        $func = $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/class/{$tableName}.php'",'',true, true, '', "\t");
        $func .= $xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', '',"\t");
        $func .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url","{$stuModuleDirname}_UPLOAD_URL",'',"\t");
        $func .= $xc->getXcEqualsOperator('$block      ', '[]', '',"\t");
        $func .= $xc->getXcEqualsOperator('$typeBlock  ', '$options[0]','',"\t");
        $func .= $xc->getXcEqualsOperator('$limit      ', '$options[1]','',"\t");
        $func .= $xc->getXcEqualsOperator('$lenghtTitle', '$options[2]','',"\t");
        $func .= $xc->getXcEqualsOperator('$helper     ', 'Helper::getInstance()','',"\t");
        $func .= $xc->getXcHandlerLine($tableName, "\t");
        $func .= $xc->getXcCriteriaCompo($critName, "\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeBlankLine();

        //content if: parent
        $contIf  = $xc->getXcEqualsOperator("\${$tableName}", "{$moduleDirname}_getMyItemIds('{$moduleDirname}_view', '{$moduleDirname}')", null, "\t");
        $crit    = $xc->getXcCriteria('', "'cid'", "'(' . implode(',', \${$tableName}) . ')'", "'IN'", true);
        $contIf  .= $xc->getXcCriteriaAdd($critName, $crit, "\t");
        $crit    = $xc->getXcCriteria('', "'{$fieldId}'", "{$moduleDirname}_block_addCatSelect(\$options)", "'IN'", true);
        $contIf2 = $xc->getXcCriteriaAdd($critName, $crit, "\t\t");
        $contIf  .= $pc->getPhpCodeConditions('1 != (count(\$options) && 0 == \$options[0])', null, null, $contIf2, false, "\t");
        $crit    = $xc->getXcCriteria('', "'{$fieldId}'", '0', "'!='", true);
        $contIf2 = $xc->getXcCriteriaAdd($critName, $crit, "\t\t");
        $contIf2 .= $xc->getXcCriteriaSetSort($critName, "'{$fieldId}'", "\t\t");
        $contIf2 .= $xc->getXcCriteriaSetOrder($critName, "'ASC'", "\t\t");
        $contIf  .= $pc->getPhpCodeConditions('$typeBlock', null, null, $contIf2, false, "\t");

        //content else: parent
        //search for SelectStatus field
        $fieldStatus = '';
        $critStatus  = '';
        foreach ($fields as $field) {
            if ($field->getVar('field_element') == 16) {
                $fieldStatus = $field->getVar('field_name');
            }
        }
        if ('' !== $fieldStatus) {
            $constant   = $xc->getXcGetConstants('PERM_GLOBAL_VIEW');
			$crit       = $xc->getXcCriteria('', "'{$fieldStatus}'", $constant, '', true);
            $critStatus .= $xc->getXcCriteriaAdd($critName, $crit, "\t\t\t");
        }

        $case1[] = $pc->getPhpCodeCommentLine("For the block: {$tableName} last",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case1[] = $critStatus;
        }
        $case1[] = $xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_date'","\t\t\t");
        $case1[] = $xc->getXcCriteriaSetOrder($critName, "'DESC'","\t\t\t");
        $case2[] = $pc->getPhpCodeCommentLine("For the block: {$tableName} new",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case2[] = $critStatus;
        }
        $crit    = $xc->getXcCriteria('', "'{$tableFieldname}_date'", 'strtotime(date(_SHORTDATESTRING))', "'>='", true);
        $case2[] = $xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $crit    = $xc->getXcCriteria('', "'{$tableFieldname}_date'", 'strtotime(date(_SHORTDATESTRING))+86400', "'<='", true);
        $case2[] = $xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case2[] = $xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_date'","\t\t\t");
        $case2[] = $xc->getXcCriteriaSetOrder($critName, "'ASC'","\t\t\t");
        $case3[] = $pc->getPhpCodeCommentLine("For the block: {$tableName} hits",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case3[] = $critStatus;
        }
        $case3[] = $xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_hits'","\t\t\t");
        $case3[] = $xc->getXcCriteriaSetOrder($critName, "'DESC'","\t\t\t");
        $case4[] = $pc->getPhpCodeCommentLine("For the block: {$tableName} top",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case4[] = $critStatus;
        }
        $case4[] = $xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case4[] = $xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_top'","\t\t\t");
        $case4[] = $xc->getXcCriteriaSetOrder($critName, "'ASC'","\t\t\t");
        $case5[] = $pc->getPhpCodeCommentLine("For the block: {$tableName} random",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case5[] = $critStatus;
        }
        $case5[] = $xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case5[] = $xc->getXcCriteriaSetSort($critName, "'RAND()'","\t\t\t");
        $cases  = [
            'last'   => $case1,
            'new'    => $case2,
            'hits'   => $case3,
            'top'    => $case4,
            'random' => $case5,
        ];
        $contSwitch = $pc->getPhpCodeCaseSwitch($cases, true, false, "\t\t");
        $contElse   = $pc->getPhpCodeSwitch('typeBlock', $contSwitch, "\t");
        //end: content else: parent
        if (1 == $fieldParent) {
            $func .= $contIf;
        } else {
            $func .= $contElse;
        }
        $func .= $pc->getPhpCodeBlankLine();

        $func .= $xc->getXcCriteriaSetLimit($critName, '$limit', "\t");
        $func .= $xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func .= $pc->getPhpCodeUnset($critName, "\t");
        $contentForeach = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            // Verify if table_fieldname is not empty
            //$lpFieldName = !empty($tableFieldname) ? substr($fieldName, 0, strpos($fieldName, '_')) : $tableName;
            $rpFieldName  = $this->getRightString($fieldName);
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_block')) {
                switch ($fieldElement) {
                    case 2:
                        $contentForeach .= $xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "\$myts->htmlSpecialChars(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 3:
                    case 4:
                        $contentForeach .= $xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 8:
                        $contentForeach .= $xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "\XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 15:
                        $contentForeach .= $xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']","formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    default:
                        $contentForeach .= $xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']","\${$tableName}All[\$i]->getVar('{$fieldName}')", null, "\t\t\t");
                        break;
                }
            }
        }
        $foreach = $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t\t");

        $func .= $pc->getPhpCodeConditions("count(\${$tableName}All)", ' > ', '0', $foreach, false, "\t");
        $func .= $pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $block;',"\t");
        $func .= $pc->getPhpCodeBlankLine();

        $ret  .= $pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_show", '$options', $func, '', false, "");

        return $ret;
    }

    /**
     * @public function getBlocksEdit
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldId
     * @param string $fieldMain
     * @param string $language
     *
     * @return string
     */
    private function getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ucfTableName     = ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;

        $ret  = $pc->getPhpCodeCommentMultiLine(['Function' => 'edit block', '@param  $options' => '', '@return' => 'string']);
        $func = $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/class/{$tableName}.php'",'',true, true, '', "\t");
        $func .= $xc->getXcEqualsOperator('$helper', 'Helper::getInstance()', '',"\t");
		$func .= $xc->getXcHandlerLine($tableName, "\t");
        $func .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url","{$stuModuleDirname}_UPLOAD_URL",'',"\t");
        $func .= $xc->getXcEqualsOperator('$form', "{$language}DISPLAY", '',"\t");
        $func .= $xc->getXcEqualsOperator('$form', "\"<input type='hidden' name='options[0]' value='\".\$options[0].\"' />\"", '.',"\t");
        $func .= $xc->getXcEqualsOperator('$form', "\"<input type='text' name='options[1]' size='5' maxlength='255' value='\" . \$options[1] . \"' />&nbsp;<br>\"", '.',"\t");
        $func .= $xc->getXcEqualsOperator('$form', "{$language}TITLE_LENGTH . \" : <input type='text' name='options[2]' size='5' maxlength='255' value='\" . \$options[2] . \"' /><br><br>\"", '.',"\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $pc->getPhpCodeBlankLine();
        $func .= $xc->getXcCriteriaCompo($critName, "\t");
        $crit = $xc->getXcCriteria('', "'{$fieldId}'", '0', "'!='", true);
        $func .= $xc->getXcCriteriaAdd($critName, $crit, "\t", "\n");
        $func .= $xc->getXcCriteriaSetSort($critName, "'{$fieldId}'","\t","\n");
        $func .= $xc->getXcCriteriaSetOrder($critName, "'ASC'","\t","\n");
        $func .= $xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func .= $pc->getPhpCodeUnset($critName, "\t");
        $func .= $xc->getXcEqualsOperator('$form', "{$language}{$stuTableName}_TO_DISPLAY . \"<br><select name='options[]' multiple='multiple' size='5'>\"", '.',"\t");
        $func .= $xc->getXcEqualsOperator('$form', "\"<option value='0' \" . (in_array(0, \$options) == false ? '' : \"selected='selected'\") . '>' . {$language}ALL_{$stuTableName} . '</option>'", '.',"\t");
        $contentForeach = $xc->getXcEqualsOperator("\${$fieldId}", "\${$tableName}All[\$i]->getVar('{$fieldId}')", '',"\t\t");
        $contentForeach .= $xc->getXcEqualsOperator('$form', "\"<option value='\" . \${$fieldId} . \"' \" . (in_array(\${$fieldId}, \$options) == false ? '' : \"selected='selected'\") . '>' . \${$tableName}All[\$i]->getVar('{$fieldMain}') . '</option>'", '.',"\t\t");
        $func .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t");
        $func .= $xc->getXcEqualsOperator('$form', "'</select>'", '.',"\t");
        $func .= $pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $form;', "\t");
        $func .= $pc->getPhpCodeBlankLine();

        $ret .= $pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_edit", '$options', $func, '', false, "");

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
        $pc             = Modulebuilder\Files\CreatePhpCode::getInstance();
        $module         = $this->getModule();
        $filename       = $this->getFileName();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableName      = $table->getVar('table_name');
        $tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MB');
        $fields         = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldId        = null;
        $fieldParent    = null;
        $fieldMain      = null;
        foreach (array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
            $fieldParent = $fields[$f]->getVar('field_parent');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $content = $this->getHeaderFilesComments($module);
        $content .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $content .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Helper'], '', '');
        $content .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $content .= $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/include/common.php'",'',true, true);
        $content .= $this->getBlocksShow($moduleDirname, $tableName, $tableFieldname, $fields, $fieldId, $fieldParent);
        $content .= $this->getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language);

        $this->create($moduleDirname, 'blocks', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

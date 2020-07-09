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
     * @param     $tablePermissions
     * @param     $fields
     * @param     $fieldId
     * @param int $fieldParent
     * @return string
     */
    private function getBlocksShow($moduleDirname, $tableName, $tableFieldname, $tablePermissions, $fields, $fieldId, $fieldParent = 0)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ucfTableName     = \ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;

        $ret  = $this->pc->getPhpCodeCommentMultiLine(['Function' => 'show block', '@param  $options' => '', '@return' => 'array']);

        $func = $this->pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/class/{$tableName}.php'",'',true, true, '', "\t");
        $func .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', '',"\t");
        $func .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url","{$stuModuleDirname}_UPLOAD_URL",'',"\t");
        $func .= $this->xc->getXcEqualsOperator('$block      ', '[]', '',"\t");
        $func .= $this->xc->getXcEqualsOperator('$typeBlock  ', '$options[0]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$limit      ', '$options[1]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$lenghtTitle', '$options[2]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$helper     ', 'Helper::getInstance()','',"\t");
        $func .= $this->xc->getXcHandlerLine($tableName, "\t");
        $func .= $this->xc->getXcCriteriaCompo($critName, "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        //content if: parent
        $contIf  = $this->xc->getXcEqualsOperator("\${$tableName}", "{$moduleDirname}_getMyItemIds('{$moduleDirname}_view', '{$moduleDirname}')", null, "\t");
        $crit    = $this->xc->getXcCriteria('', "'cid'", "'(' . \implode(',', \${$tableName}) . ')'", "'IN'", true);
        $contIf  .= $this->xc->getXcCriteriaAdd($critName, $crit, "\t");
        $crit    = $this->xc->getXcCriteria('', "'{$fieldId}'", "{$moduleDirname}_block_addCatSelect(\$options)", "'IN'", true);
        $contIf2 = $this->xc->getXcCriteriaAdd($critName, $crit, "\t\t");
        $contIf  .= $this->pc->getPhpCodeConditions('1 != (\count(\$options) && 0 == \$options[0])', null, null, $contIf2, false, "\t");
        $crit    = $this->xc->getXcCriteria('', "'{$fieldId}'", '0', "'!='", true);
        $contIf2 = $this->xc->getXcCriteriaAdd($critName, $crit, "\t\t");
        $contIf2 .= $this->xc->getXcCriteriaSetSort($critName, "'{$fieldId}'", "\t\t");
        $contIf2 .= $this->xc->getXcCriteriaSetOrder($critName, "'ASC'", "\t\t");
        $contIf  .= $this->pc->getPhpCodeConditions('$typeBlock', null, null, $contIf2, false, "\t");

        //content else: parent
        //search for SelectStatus field
        $fieldStatus = '';
        $critStatus  = '';
        $fieldDate   = '';
        if (1 == $tablePermissions) {
            foreach ($fields as $field) {
                if ($field->getVar('field_element') == 16) {
                    $fieldStatus = $field->getVar('field_name');
                }
                if ($field->getVar('field_element') == 15 || $field->getVar('field_element') == 21) {
                    $fieldDate = $field->getVar('field_name');
                }
            }
            if ('' !== $fieldStatus) {
                $constant = $this->xc->getXcGetConstants('PERM_GLOBAL_VIEW');
                $crit = $this->xc->getXcCriteria('', "'{$fieldStatus}'", $constant, "'>'", true);
                $critStatus .= $this->xc->getXcCriteriaAdd($critName, $crit, "\t\t\t");
            }
        }

        $case1 = [];
        $case2 = [];
        $case3 = [];
        $case4 = [];
        $case5 = [];

        $case1[] = $this->pc->getPhpCodeCommentLine("For the block: {$tableName} last",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case1[] = $critStatus;
        }
        $case1[] = $this->xc->getXcCriteriaSetSort($critName, "'{$fieldDate}'","\t\t\t");
        $case1[] = $this->xc->getXcCriteriaSetOrder($critName, "'DESC'","\t\t\t");
        $case2[] = $this->pc->getPhpCodeCommentLine("For the block: {$tableName} new",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case2[] = $critStatus;
        }
        $crit    = $this->xc->getXcCriteria('', "'{$fieldDate}'", '\\strto\time(date(_SHORTDATESTRING))', "'>='", true);
        $case2[] = $this->xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $crit    = $this->xc->getXcCriteria('', "'{$fieldDate}'", '\\strto\time(date(_SHORTDATESTRING))+86400', "'<='", true);
        $case2[] = $this->xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case2[] = $this->xc->getXcCriteriaSetSort($critName, "'{$fieldDate}'","\t\t\t");
        $case2[] = $this->xc->getXcCriteriaSetOrder($critName, "'ASC'","\t\t\t");
        $case3[] = $this->pc->getPhpCodeCommentLine("For the block: {$tableName} hits",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case3[] = $critStatus;
        }
        $case3[] = $this->xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_hits'","\t\t\t");
        $case3[] = $this->xc->getXcCriteriaSetOrder($critName, "'DESC'","\t\t\t");
        $case4[] = $this->pc->getPhpCodeCommentLine("For the block: {$tableName} top",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case4[] = $critStatus;
        }
        $case4[] = $this->xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case4[] = $this->xc->getXcCriteriaSetSort($critName, "'{$tableFieldname}_top'","\t\t\t");
        $case4[] = $this->xc->getXcCriteriaSetOrder($critName, "'ASC'","\t\t\t");
        $case5[] = $this->pc->getPhpCodeCommentLine("For the block: {$tableName} random",'',"\t\t\t");
        if ('' !== $fieldStatus) {
            $case5[] = $critStatus;
        }
        $case5[] = $this->xc->getXcCriteriaAdd($critName, $crit,"\t\t\t");
        $case5[] = $this->xc->getXcCriteriaSetSort($critName, "'RAND()'","\t\t\t");
        $cases  = [
            'last'   => $case1,
            'new'    => $case2,
            'hits'   => $case3,
            'top'    => $case4,
            'random' => $case5,
        ];
        $contSwitch = $this->pc->getPhpCodeCaseSwitch($cases, true, false, "\t\t");
        $contElse   = $this->pc->getPhpCodeSwitch('typeBlock', $contSwitch, "\t");
        //end: content else: parent
        if (1 == $fieldParent) {
            $func .= $contIf;
        } else {
            $func .= $contElse;
        }
        $func .= $this->pc->getPhpCodeBlankLine();

        $func .= $this->xc->getXcCriteriaSetLimit($critName, '$limit', "\t");
        $func .= $this->xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func .= $this->pc->getPhpCodeUnset($critName, "\t");
        $contentForeach = '';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            // Verify if table_fieldname is not empty
            //$lpFieldName = !empty($tableFieldname) ? \substr($fieldName, 0, \strpos($fieldName, '_')) : $tableName;
            $rpFieldName  = $this->getRightString($fieldName);
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_block')) {
                switch ($fieldElement) {
                    case 2:
                        $contentForeach .= $this->xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "\$myts->htmlSpecialChars(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 3:
                    case 4:
                        $contentForeach .= $this->xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "\strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 8:
                        $contentForeach .= $this->xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']", "\XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    case 15:
                        $contentForeach .= $this->xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']","\\formatTimestamp(\${$tableName}All[\$i]->getVar('{$fieldName}'))", null, "\t\t\t");
                        break;
                    default:
                        $contentForeach .= $this->xc->getXcEqualsOperator("\$block[\$i]['{$rpFieldName}']","\${$tableName}All[\$i]->getVar('{$fieldName}')", null, "\t\t\t");
                        break;
                }
            }
        }
        $foreach = $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t\t");

        $func .= $this->pc->getPhpCodeConditions("\count(\${$tableName}All)", ' > ', '0', $foreach, false, "\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $block;',"\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        $ret  .= $this->pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_show", '$options', $func, '', false, "");

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
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $stuTableName     = \mb_strtoupper($tableName);
        $ucfTableName     = \ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;

        $ret  = $this->pc->getPhpCodeCommentMultiLine(['Function' => 'edit block', '@param  $options' => '', '@return' => 'string']);
        $func = $this->pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/class/{$tableName}.php'",'',true, true, '', "\t");
        $func .= $this->xc->getXcEqualsOperator('$helper', 'Helper::getInstance()', '',"\t");
		$func .= $this->xc->getXcHandlerLine($tableName, "\t");
        $func .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url","{$stuModuleDirname}_UPLOAD_URL",'',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}DISPLAY", '',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<input type='hidden' name='options[0]' value='\".\$options[0].\"' />\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<input type='text' name='options[1]' size='5' maxlength='255' value='\" . \$options[1] . \"' />&nbsp;<br>\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}TITLE_LENGTH . \" : <input type='text' name='options[2]' size='5' maxlength='255' value='\" . \$options[2] . \"' /><br><br>\"", '.',"\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->xc->getXcCriteriaCompo($critName, "\t");
        $crit = $this->xc->getXcCriteria('', "'{$fieldId}'", '0', "'!='", true);
        $func .= $this->xc->getXcCriteriaAdd($critName, $crit, "\t", "\n");
        $func .= $this->xc->getXcCriteriaSetSort($critName, "'{$fieldId}'","\t","\n");
        $func .= $this->xc->getXcCriteriaSetOrder($critName, "'ASC'","\t","\n");
        $func .= $this->xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func .= $this->pc->getPhpCodeUnset($critName, "\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}{$stuTableName}_TO_DISPLAY . \"<br><select name='options[]' multiple='multiple' size='5'>\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<option value='0' \" . (\in_array(0, \$options) == false ? '' : \"selected='selected'\") . '>' . {$language}ALL_{$stuTableName} . '</option>'", '.',"\t");
        $contentForeach = $this->xc->getXcEqualsOperator("\${$fieldId}", "\${$tableName}All[\$i]->getVar('{$fieldId}')", '',"\t\t");
        $contentForeach .= $this->xc->getXcEqualsOperator('$form', "\"<option value='\" . \${$fieldId} . \"' \" . (\in_array(\${$fieldId}, \$options) == false ? '' : \"selected='selected'\") . '>' . \${$tableName}All[\$i]->getVar('{$fieldMain}') . '</option>'", '.',"\t\t");
        $func .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "'</select>'", '.',"\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $form;', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        $ret .= $this->pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_edit", '$options', $func, '', false, "");

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
        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $table            = $this->getTable();
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $tableFieldname   = $table->getVar('table_fieldname');
        $tablePermissions = $table->getVar('table_permissions');
        $language         = $this->getLanguage($moduleDirname, 'MB');
        $fields           = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldId          = null;
        $fieldParent      = null;
        $fieldMain        = null;
        foreach (\array_keys($fields) as $f) {
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
        $content .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $content .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Helper'], '', '');
        $content .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $content .= $this->pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/include/common.php'",'',true, true);
        $content .= $this->getBlocksShow($moduleDirname, $tableName, $tableFieldname, $tablePermissions, $fields, $fieldId, $fieldParent);
        $content .= $this->getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language);

        $this->create($moduleDirname, 'blocks', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

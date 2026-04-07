<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Blocks;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\{
    Files,
    Constants
};


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
 *
 */

/**
 * Class BlocksFilesSpotlight.
 */
class BlocksFilesSpotlight extends Files\CreateFile
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
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

        /**
     * @static function getInstance
     *
     * @return BlocksFilesSpotlight|false
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
     * @param     $tablePermissions
     * @param     $fields
     * @param     $fieldId
     * @return string
     */
    private function getBlocksShow($moduleDirname, $tableName, $tablePermissions, $fields, $fieldId)
    {
        $ucfTableName     = \ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ucfModuleDirname = \ucfirst($moduleDirname);

        $configMaxchar = 0;
        foreach (\array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (Constants::FIELD_ELE_TEXTAREA == $fieldElement || Constants::FIELD_ELE_DHTMLTEXTAREA == $fieldElement) {
                $configMaxchar = 1;
            }
        }

        $ret  = $this->pc->getPhpCodeCommentMultiLine(['Function' => 'show block', '@param  $options' => '', '@return' => 'array']);

        //$func .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', '',"\t");
        $func = $this->xc->getXcEqualsOperator('$block      ', '[]', '',"\t");
        $func .= '//' . $this->xc->getXcEqualsOperator('$typeBlock  ', '$options[0]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$limit      ', '$options[1]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$lenghtTitle  ', '$options[2]','',"\t");
        $func .= $this->xc->getXcEqualsOperator('$helper     ', 'Helper::getInstance()','',"\t");
        if (1 === $configMaxchar) {
            $func .= $this->xc->getXcEqualsOperator('$utility      ', "new \XoopsModules\\{$ucfModuleDirname}\Utility()", '',"\t");
        }
        $func .= $this->xc->getXcHandlerLine($tableName, "\t");
        $func .= $this->xc->getXcCriteriaCompo($critName, "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        //Criteria for status field
        $fieldStatus = '';
        if (1 == $tablePermissions) {
            foreach ($fields as $field) {
                if ($field->getVar('field_element') == 16) {
                    $fieldStatus = $field->getVar('field_name');
                }
            }
            if ('' !== $fieldStatus) {
                $constant = $this->xc->getXcGetConstants('STATUS_OFFLINE');
                $crit = $this->xc->getXcCriteria('', "'{$fieldStatus}'", $constant, "'>'", true);
                $func .= $this->pc->getPhpCodeCommentLine("Criteria for status field",'',"\t");
                $func .= $this->xc->getXcCriteriaAdd($critName, $crit, "\t");
                $func .= $this->pc->getPhpCodeBlankLine();
            }
        }

        $crit   = $this->xc->getXcCriteria('', "'{$fieldId}'", "'(' . \implode(',', \$options) . ')'", "'IN'", true);
        $contIf = $this->xc->getXcCriteriaAdd($critName, $crit, "\t\t");
        $contIf .= $this->xc->getXcEqualsOperator('$limit', '0', '',"\t\t");
        $func   .= $this->pc->getPhpCodeConditions('\count($options) > 0 && (int)$options[0] > 0', '', '', $contIf, false, "\t");
        $func   .= $this->pc->getPhpCodeBlankLine();

        $func   .= $this->xc->getXcCriteriaSetSort($critName, "'{$fieldId}'","\t");
        $func   .= $this->xc->getXcCriteriaSetOrder($critName, "'DESC'","\t");
        $func   .= $this->xc->getXcCriteriaSetLimit($critName, '$limit', "\t");
        $func   .= $this->xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func   .= $this->pc->getPhpCodeUnset($critName, "\t");
        $contentForeach = $this->xc->getXcEqualsOperator("\${$tableName}","\${$tableName}All[\$i]->getValues{$ucfTableName}()", '', "\t\t\t");
        $contentForeach .= $this->pc->getPhpCodeCommentMultiLine([
            'If you want to use the parameter for limits you have to adapt the line where it should be applied' => '',
            'e.g. change' => '',
            "\t\$block[\$i]['title']" => "= \${$tableName}All[\$i]->getVar('art_title');",
            'into' => '',
            "\t" . '$myTitle' => "= \${$tableName}All[\$i]->getVar('art_title');",
            "\t" . 'if ($limit > 0) {' => '',
            "\t\t" . '$myTitle' => '= \substr($myTitle, 0, (int)$limit);',
            "\t" . '}' => '',
            "\t\$block[\$i]['title'] = " => '$myTitle;',
        ], "\t\t\t", false);


        $lenName = 0;
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_block')) {
                switch ($fieldElement) {
                    case Constants::FIELD_ELE_TEXTAREA:
                    case Constants::FIELD_ELE_DHTMLTEXTAREA:
                        $lenName  = max(strlen($this->getRightString($fieldName)) + 6, $lenName);
                        break;
                    case Constants::FIELD_ELE_SELECTUSER:
                    case Constants::FIELD_ELE_TEXTDATESELECT:
                    case Constants::FIELD_ELE_SELECTSTATUS:
                    case Constants::FIELD_ELE_RADIOYN:
                    case Constants::FIELD_ELE_RADIO_ONOFFLINE:
                    case Constants::FIELD_ELE_DATETIME:
                        $lenName  = max(strlen($this->getRightString($fieldName)) + 5, $lenName);
                        break;
                    case 0:
                    default:
                        $lenName  = max(strlen($this->getRightString($fieldName)), $lenName);
                        break;
                }
            }
        }
        $lenName += 15;
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $rpFieldName  = $this->getRightString($fieldName);
            $fieldElement = $fields[$f]->getVar('field_element');

            if (0 == $f) {
                $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['id']", $lenName), "\${$tableName}['{$fieldId}']", null, "\t\t\t");
            }
            if (1 == $fields[$f]->getVar('field_block')) {
                switch ($fieldElement) {
                    case Constants::FIELD_ELE_TEXTAREA:
                    case Constants::FIELD_ELE_DHTMLTEXTAREA:
                        $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['{$rpFieldName}_text']", $lenName), "\${$tableName}['{$rpFieldName}_text']", null, "\t\t\t");
                        $truncate  =  "\$utility::truncateHtml(\$block[\$i]['{$rpFieldName}_text'], \$lenghtTitle)";
                        $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['{$rpFieldName}_short']", $lenName), $truncate, false, "\t\t\t");
                        break;
                    case Constants::FIELD_ELE_TEXTDATESELECT:
                    case Constants::FIELD_ELE_DATETIME:
                    case Constants::FIELD_ELE_SELECTUSER:
                        $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['{$rpFieldName}_text']", $lenName), "\${$tableName}['{$rpFieldName}_text']", null, "\t\t\t");
                        break;
                    case Constants::FIELD_ELE_SELECTSTATUS:
                    case Constants::FIELD_ELE_RADIOYN:
                    case Constants::FIELD_ELE_RADIO_ONOFFLINE:
                        $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['{$rpFieldName}_text']", $lenName),"\${$tableName}['{$rpFieldName}_text']", null, "\t\t\t");
                        break;
                    case 0:
                    case Constants::FIELD_ELE_TEXT:
                    default:
                        $contentForeach .= $this->xc->getXcEqualsOperator(str_pad("\$block[\$i]['{$rpFieldName}']", $lenName),"\${$tableName}['{$rpFieldName}']", null, "\t\t\t");
                        break;
                }
            }
        }
        $foreach = $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t\t");

        $func .= $this->pc->getPhpCodeConditions("\count(\${$tableName}All)", ' > ', '0', $foreach, false, "\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_url", "\\{$stuModuleDirname}_URL", true ,"\t");
        $config = $this->xc->getXcGetConfig('table_type');
        $func .= $this->xc->getXcXoopsTplAssign('table_type', $config, true, "\t");

        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $block;',"\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        $ret  .= $this->pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_spotlight_show", '$options', $func, '');

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
    private function getBlocksEdit(string $moduleDirname, string $tableName, string $fieldId, string $fieldMain, string $language)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $stuTableName     = \mb_strtoupper($tableName);
        $ucfTableName     = \ucfirst($tableName);
        $critName         = 'cr' . $ucfTableName;

        $ret  = $this->pc->getPhpCodeCommentMultiLine(['Function' => 'edit block', '@param  $options' => '', '@return' => 'string']);
        $func = $this->xc->getXcEqualsOperator('$helper', 'Helper::getInstance()', '',"\t");
		$func .= $this->xc->getXcHandlerLine($tableName, "\t");
        $func .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url","\\{$stuModuleDirname}_UPLOAD_URL",true,"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}DISPLAY_SPOTLIGHT . ' : '", '',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<input type='hidden' name='options[0]' value='\".\$options[0].\"' >\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<input type='text' name='options[1]' size='5' maxlength='255' value='\" . \$options[1] . \"' >&nbsp;<br>\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}TITLE_LENGTH . \" : <input type='text' name='options[2]' size='5' maxlength='255' value='\" . \$options[2] . \"' ><br><br>\"", '.',"\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeArrayShift('$options', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->xc->getXcCriteriaCompo($critName, "\t");
        $crit = $this->xc->getXcCriteria('', "'{$fieldId}'", '0', "'!='", true);
        $func .= $this->xc->getXcCriteriaAdd($critName, $crit, "\t");
        $func .= $this->xc->getXcCriteriaSetSort($critName, "'{$fieldId}'","\t");
        $func .= $this->xc->getXcCriteriaSetOrder($critName, "'ASC'","\t");
        $func .= $this->xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", "\t");
        $func .= $this->pc->getPhpCodeUnset($critName, "\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "{$language}{$stuTableName}_TO_DISPLAY . \"<br><select name='options[]' multiple='multiple' size='5'>\"", '.',"\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "\"<option value='0' \" . (!\in_array(0, \$options) && !\in_array('0', \$options) ? '' : \"selected='selected'\") . '>' . {$language}ALL_{$stuTableName} . '</option>'", '.',"\t");
        $contentForeach = $this->xc->getXcEqualsOperator("\${$fieldId}", "\${$tableName}All[\$i]->getVar('{$fieldId}')", '',"\t\t");
        $contentForeach .= $this->xc->getXcEqualsOperator("\${$fieldMain}", '\htmlspecialchars((string)' . "\${$tableName}All[\$i]->getVar('{$fieldMain}'), ENT_QUOTES | ENT_HTML5)", '',"\t\t");
        $contentForeach .= $this->xc->getXcEqualsOperator('$form', "\"<option value='\" . \${$fieldId} . \"' \" . (!\in_array(\${$fieldId}, \$options) ? '' : \"selected='selected'\") . '>' . \${$fieldMain} . '</option>'", '.',"\t\t");
        $func .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, "\t");
        $func .= $this->xc->getXcEqualsOperator('$form', "'</select>'", '.',"\t");
        $func .= $this->pc->getPhpCodeBlankLine();
        $func .= $this->getSimpleString('return $form;', "\t");
        $func .= $this->pc->getPhpCodeBlankLine();

        $ret .= $this->pc->getPhpCodeFunction("b_{$moduleDirname}_{$tableName}_spotlight_edit", '$options', $func, '');

        return $ret;

    }

    /**
     * @public function render
     *
     * @return string
     */
    public function render()
    {
        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $table            = $this->getTable();
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $tablePermissions = $table->getVar('table_permissions');
        $language         = $this->getLanguage($moduleDirname, 'MB');
        $fields           = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldId          = null;
        $fieldMain        = null;
        foreach (\array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
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
        $content .= $this->pc->getPhpCodeIncludeDir("\XOOPS_ROOT_PATH . '/modules/{$moduleDirname}/include/common.php'",'',true, true);
        $content .= $this->getBlocksShow($moduleDirname, $tableName, $tablePermissions, $fields, $fieldId);
        $content .= $this->getBlocksEdit($moduleDirname, $tableName, $fieldId, $fieldMain, $language);

        $this->create($moduleDirname, 'blocks', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

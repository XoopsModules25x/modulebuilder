<?php

namespace XoopsModules\Modulebuilder\Files;

use XoopsModules\Modulebuilder;

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
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class CreateXoopsCode.
 */
class CreateXoopsCode
{
    /**
     * @static function getInstance
     * @param null
     */

    /**
     * @return Modulebuilder\Files\CreateXoopsCode
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
     * @public function getXcSwitch
     * @param string $op
     * @param array $cases
     * @param bool $defaultAfterCase
     * @param bool $default
     * @param string $t - Indentation
     *
     * @param bool $isString
     * @param bool $isConst
     * @return string
     */
    public function getXcSwitch($op = '', $cases = [], $defaultAfterCase = false, $default = false, $t = '', $isString = true, $isConst = false)
    {
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, $defaultAfterCase, $default, $t . "\t", $isConst);

        return $pc->getPhpCodeSwitch($op, $contentSwitch, $t, $isString);
    }

    /**
     * @public function getXcEqualsOperator
     * @param $var
     * @param $value
     * @param $interlock
     * @param $t - Indentation
     *
     * @return string
     */
    public function getXcEqualsOperator($var, $value, $interlock = null, $t = '')
    {
        return "{$t}{$var} {$interlock}= {$value};\n";
    }

    /**
     * @public function getXcAnchorFunction
     * @param $anchor
     * @param $name
     * @param $vars
     * @param $close
     *
     * @return string
     */
    public function getXcAnchorFunction($anchor, $name, $vars, $close = false)
    {
        $semicolon = false !== $close ? ';' : '';

        return "\${$anchor}->{$name}({$vars}){$semicolon}";
    }

    /**
     * @public function getXcSetVarObj
     * @param $tableName
     * @param $fieldName
     * @param $var
     * @param $t
     * @return string
     */
    public function getXcSetVarObj($tableName, $fieldName, $var, $t = '')
    {
        return "{$t}\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /**
     * @public function getXcGetVar
     * @param string $varLeft
     * @param string $handle
     * @param string $var
     * @param bool $isParam
     * @param string $t
     *
     * @param string $format
     * @return string
     */
    public function getXcGetVar($varLeft = '', $handle = '', $var = '', $isParam = false, $t = '', $format = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$varLeft} = \${$handle}->getVar('{$var}'{$format});\n";
        } else {
            $ret = "\${$handle}->getVar('{$var}'{$format})";
        }

        return $ret;
    }

    /**
     * @public function getXcAddItem
     * @param $varLeft
     * @param $paramLeft
     * @param $paramRight
     * @param $t
     *
     * @return string
     */
    public function getXcAddItem($varLeft = '', $paramLeft = '', $paramRight = '', $t = '')
    {
        return "{$t}\${$varLeft}->addItem({$paramLeft}, {$paramRight});\n";
    }

    /**
     * @public function getXcGetGroupIds
     * @param string $var
     * @param string $anchor
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param string $t
     * @return string
     */
    public function getXcGetGroupIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getGroupIds({$param1}, {$param2}, {$param3});\n";
    }

    /**
     * @public function getXcGetItemIds
     * @param string $var
     * @param string $anchor
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param string $t
     * @return string
     */
    public function getXcGetItemIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getItemIds({$param1}, {$param2}, {$param3});\n";
    }

    /**
     * @public function getXcSetVarTextDateSelect
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName, $t = '')
    {
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $rightField    = $tf->getRightString($fieldName);
        $ucfRightField = \ucfirst($rightField);
        $value         = "\DateTime::createFromFormat(\_SHORTDATESTRING, Request::getString('{$fieldName}'))";
        $ret           = $this->getXcEqualsOperator("\${$tableSoleName}{$ucfRightField}Obj", $value, null, $t);
        $ret           .= $this->getXcSetVarObj($tableName, $fieldName, "\${$tableSoleName}{$ucfRightField}Obj->getTimestamp()", $t);

        return $ret;
    }

    /**
     * @public function getXcSetVarDateTime
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcSetVarDateTime($tableName, $tableSoleName, $fieldName, $t = '')
    {
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $rightField    = $tf->getRightString($fieldName);
        $ucfRightField = \ucfirst($rightField);
        $request       = "Request::getArray('{$fieldName}')";
        $var           = "\${$tableSoleName}{$ucfRightField}";
        $varArr        = "\${$tableSoleName}{$ucfRightField}Arr";
        $ret           = $this->getXcEqualsOperator($varArr, $request, null, $t);
        $varObj        = "\${$tableSoleName}{$ucfRightField}Obj";
        $value         = "\DateTime::createFromFormat(\_SHORTDATESTRING, {$varArr}['date'])";
        $ret           .= $this->getXcEqualsOperator($varObj, $value, null, $t);
        $ret           .= "{$t}{$varObj}->setTime(0, 0, 0);\n";
        $value         = "{$varObj}->getTimestamp() + (int){$varArr}['time']";
        $ret           .= $this->getXcEqualsOperator($var, $value, null, $t);
        $ret           .= $this->getXcSetVarObj($tableName, $fieldName, $var, $t);

        return $ret;
    }

    /**
     * @public function getXcSetVarCheckBoxOrRadioYN
     * @param $tableName
     * @param $fieldName
     * @param $t
     * @return string
     */
    public function getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName, $t = '')
    {
        return $this->getXcSetVarObj($tableName, $fieldName, "Request::getInt('{$fieldName}', 0)", $t);
    }

    /**
     * @public function getXcMediaUploader
     * @param $var
     * @param $dirPath
     * @param $mimetype
     * @param $maxsize
     * @param string $t
     * @return string
     */
    public function getXcMediaUploader($var, $dirPath, $mimetype, $maxsize, $t = '')
    {
        $mimetypes_file = $this->getXcGetConfig($mimetype);
        $maxsize_file   = $this->getXcGetConfig($maxsize);

        return "{$t}\${$var} = new \XoopsMediaUploader({$dirPath}, 
													{$mimetypes_file}, 
													{$maxsize_file}, null, null);\n";
    }

    /**
     * @public function getXcXoopsCaptcha
     * @param $var
     * @param $instance
     * @param $t
     *
     * @return string
     */
    public function getXcGetInstance($var = '', $instance = '', $t = '')
    {
        return "{$t}\${$var} = {$instance}::getInstance();\n";
    }

    /**
     * @public function getXcGetConfig
     * @param $name
     * @return string
     */
    public function getXcGetConfig($name)
    {
        return "\$helper->getConfig('{$name}')";
    }

    /**
     * @public function getXcIdGetVar
     * @param $lpFieldName
     * @param $t
     * @return string
     */
    public function getXcIdGetVar($lpFieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['id'] = \$i;\n";
    }

    /**
     * @public function getXcGetVarAll
     * @param $lpFieldName
     * @param $rpFieldName
     * @param $tableName
     * @param $fieldName
     * @param $t
     * @return string
     */
    public function getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
    }

    /**
     * @public function getXcHelperGetInstance
     * @param        $moduleDirname
     *
     * @param string $t
     * @return string
     */
    public function getXcHelperGetInstance($moduleDirname, $t = '')
    {
        $ucfModuleDirname = \ucfirst($moduleDirname);
        $ret              = "{$t}// Get instance of module\n";
        $ret              .= "{$t}\$helper = \XoopsModules\\{$ucfModuleDirname}\Helper::getInstance();\n";

        return $ret;
    }

    /**
     * @public function getXcFormatTimeStamp
     * @param        $left
     * @param        $value
     * @param string $format
     * @param string $t
     * @return string
     */
    public function getXcFormatTimeStamp($left, $value, $format = 's', $t = '')
    {
        return "{$t}\${$left} = \\formatTimestamp({$value}, '{$format}');\n";
    }

    /**
     * @public function getXcTopicGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param        $fieldNameTopic
     * @param string $t
     * @return string
     */
    public function getXcTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $pc          = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret         = $pc->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $fieldParent = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret         .= $this->getXcHandlerGet($rpFieldName, $fieldParent, '', $tableNameTopic . 'Handler', false, $t);
        $ret         .= $this->getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

        return $ret;
    }

    /**
     * @public function getXcParentTopicGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableSoleNameTopic
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param string $t
     * @return string
     */
    public function getXcParentTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent, $t = '')
    {
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $parentTopic  = $pc->getPhpCodeCommentLine('Get', $tableNameTopic . ' Handler', $t . "\t");
        $parentTopic  .= $this->getXcHandlerLine($tableNameTopic, $t . "\t");
        $elseGroups   = $this->getXcEqualsOperator('$groups', '\XOOPS_GROUP_ANONYMOUS');
        $ret          = $pc->getPhpCodeConditions("!isset(\${$tableNameTopic}Handler", '', '', $parentTopic, $elseGroups);
        $ret          .= $this->getXcGetVarFromID("\${$lpFieldName}['{$rpFieldName}']", $tableNameTopic, $tableSoleNameTopic, $tableName, $fieldNameParent, $t);

        return $ret;
    }

    /**
     * @public function getXcGetVarFromID
     * @param        $left
     * @param        $anchor
     * @param        $var
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcGetVarFromID($left, $anchor, $var, $tableName, $fieldName, $t = '')
    {
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret          = $pc->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $getVarFromID = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');
        $rightGet     = $this->getXcAnchorFunction($anchor . 'Handler', 'get' . $var . 'FromId', $getVarFromID);
        $ret          .= $this->getXcEqualsOperator($left, $rightGet, null, $t);

        return $ret;
    }

    /**
     * @public function getXcGetVarUploadImage
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    /*
    public function getXcGetVarUploadImage($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= $this->getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pc->getPhpCodeTernaryOperator("{$lpFieldName}['{$rpFieldName}']", "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);

        return $ret;
    }
    */

    /**
     * @public function getXcGetVarUrlFile
     * @param $lpFieldName
     * @param $rpFieldName
     * @param $tableName
     * @param $fieldName
     * @return string
     */
    public function getXcGetVarUrlFile($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return $this->getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
    }

    /**
     * @public function getXcGetVarTextArea
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcGetVarTextArea($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $getVar = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');

        return $t . $pc->getPhpCodeStripTags("{$lpFieldName}['{$rpFieldName}']", $getVar, false, $t);
    }

    /**
     * @public function getXcGetVarSelectUser
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcGetVarSelectUser($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = \XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /**
     * @public function getXcGetVarTextDateSelect
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getXcGetVarTextDateSelect($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = \\formatTimestamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /**
     * @public function getXcUserHeader
     * @param        $moduleDirname
     * @param        $tableName
     * @param string $t
     * @return string
     */
    public function getXcXoopsOptionTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /**
     * @public function getXcUserHeader
     * @param $moduleDirname
     * @param $tableName
     * @return string
     */
    public function getXcUserHeader($moduleDirname, $tableName)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXcXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /**
     * @public function getXcPermissionsHeader
     * @param null
     * @return string
     */
    public function getXcPermissionsHeader()
    {
        $pc         = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret        = $pc->getPhpCodeCommentLine('Permission');
        $ret        .= $pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret        .= $this->getXcXoopsHandler('groupperm');
        $groups     = $this->getXcEqualsOperator('$groups', '$xoopsUser->getGroups()');
        $elseGroups = $this->getXcEqualsOperator('$groups', '\XOOPS_GROUP_ANONYMOUS');
        $ret        .= $pc->getPhpCodeConditions('\is_object($xoopsUser)', '', $type = '', $groups, $elseGroups);

        return $ret;
    }

    /**
     * @public function getXcGetFieldId
     *
     * @param $fields
     *
     * @return string
     */
    public function getXcGetFieldId($fields)
    {
        $fieldId = 'id';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
        }

        return $fieldId;
    }

    /**
     * @public function getXcGetFieldName
     *
     * @param $fields
     *
     * @return string
     */
    public function getXcGetFieldName($fields)
    {
        $fieldName = '';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
    }

    /**
     * @public function getXcGetFieldParentId
     *
     * @param $fields
     *
     * @return string
     */
    public function getXcGetFieldParentId($fields)
    {
        $fieldPid = 'pid';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }

        return $fieldPid;
    }

    /**
     * @public function getXcUserSaveElements
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fields
     * @return string
     */
    public function getXcUserSaveElements($moduleDirname, $tableName, $tableSoleName, $fields)
    {
        $axc            = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
        $ret            = '';
        $fieldMain      = '';
        $countUploader  = 0;
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $axc->getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, '', $countUploader);
                $countUploader++;
            } elseif (14 == $fieldElement) {
                $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, '', '', $countUploader);
                $countUploader++;
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName);
            } else {
                $ret .= $this->getXcSetVarObj($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsRequest
     * @param string $left
     * @param string $var1
     * @param string $var2
     * @param string $type
     * @param bool   $method
     * @param string $t
     * @return string
     */
    public function getXcXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $method = false, $t = '')
    {
        $ret     = '';
        $intVars = ('' != $var2) ? "'{$var1}', {$var2}" : "'{$var1}'";
        if ('String' === $type) {
            $ret .= "{$t}\${$left} = Request::getString('{$var1}', '{$var2}');\n";
        } elseif ('Int' === $type) {
            $ret .= "{$t}\${$left} = Request::getInt({$intVars});\n";
        } elseif ('Int' === $type && false !== $method) {
            $ret .= "{$t}\${$left} = Request::getInt({$intVars}, '{$method}');\n";
        } else {
            $ret .= "{$t}\${$left} = Request::get{$type}('{$var1}', '{$var2}');\n";
        }

        return $ret;
    }

    /**
     * @public function getXcAddRight
     *
     * @param        $anchor
     * @param string $permString
     * @param string $var
     * @param string $groups
     * @param string $mid
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcAddRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     * @public function getXcCheckRight
     *
     * @param        $anchor
     * @param string $permString
     * @param string $var
     * @param string $groups
     * @param string $mid
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcCheckRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     * @public function getXcAddRight
     *
     * @param        $anchor
     * @param string $permString
     * @param string $mid
     * @param string $var
     * @param bool $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcDeleteRight($anchor, $permString = '', $mid = '', $var = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$anchor}->deleteByModule({$mid}, '{$permString}', {$var});\n";
        } else {
            $ret = "\${$anchor}->deleteByModule({$mid}, '{$permString}', {$var})";
        }
        return $ret;
    }

    /**
     * @public function getXcHandlerLine
     * @param $tableName
     * @param string $t
     * @return string
     */
    public function getXcHandlerLine($tableName, $t = '')
    {
        $ucfTableName = \ucfirst($tableName);
        return "{$t}\${$tableName}Handler = \$helper->getHandler('{$ucfTableName}');\n";
    }

    /**
     * @public function getXcHandlerCreateObj
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcHandlerCreateObj($tableName, $t = '')
    {
        return "{$t}\${$tableName}Obj = \${$tableName}Handler->create();\n";
    }

    /**
     * @public function getXcHandlerGetObj
     *
     * @param        $tableName
     * @param        $ccFieldId
     * @param string $t
     * @return string
     */
    public function getXcHandlerGetObj($tableName, $ccFieldId, $t = '')
    {
        return "{$t}\${$tableName}Obj = \${$tableName}Handler->get(\${$ccFieldId});\n";
    }

    /**
     * @public function getXcHandlerCountObj
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcHandlerCountObj($tableName, $t = '')
    {
        $ucfTableName = \ucfirst($tableName);
        $ret          = "{$t}\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();\n";

        return $ret;
    }

    /**
     * @public function getXcClearCount
     * @param  $left
     * @param  $anchor
     * @param  $params
     * @param  $t
     *
     * @return string
     */
    public function getXcHandlerCountClear($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getCount({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcHandlerAllObj
     *
     * @param        $tableName
     * @param string $fieldMain
     * @param string $start
     * @param string $limit
     *
     * @param string $t
     * @return string
     */
    public function getXcHandlerAllObj($tableName, $fieldMain = '', $start = '0', $limit = '0', $t = '')
    {
        $ucfTableName = \ucfirst($tableName);
        $startLimit   = ('0' != $limit) ? "{$start}, {$limit}" : '0';
        $params       = ('' != $fieldMain) ? "{$startLimit}, '{$fieldMain}'" : $startLimit;
        $ret          = "{$t}\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcHandlerAllClear
     * @param        $left
     * @param string $anchor
     * @param string $params
     * @param string $t
     * @return string
     */
    public function getXcHandlerAllClear($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getAll({$params});\n";

        return $ret;
    }

    /**
     * @public function getXcHandlerGet
     *
     * @param        $left
     * @param        $var
     * @param string $obj
     * @param string $handler
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcHandlerGet($left, $var, $obj = '', $handler = 'Handler', $isParam = false, $t = '')
    {
        if ($isParam) {
            $ret = "\${$left}{$handler}->get(\${$var})";
        } else {
            $ret = "{$t}\${$left}{$obj} = \${$handler}->get(\${$var});\n";
        }

        return $ret;
    }

    /**
     * @public function getXcHandler
     *
     * @param $left
     * @param $var
     * @param $obj
     * @param $handler
     *
     * @return string
     */
    public function getXcHandlerInsert($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->insert(\${$var}{$obj})";
    }

    /**
     * @public   function getXcHandlerDelete
     *
     * @param        $left
     * @param        $var
     * @param string $obj
     * @param string $handler
     * @return string
     */
    public function getXcHandlerDelete($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->delete(\${$var}{$obj})";
    }

    /**
     * @public function getXcGetValues
     *
     * @param        $tableName
     * @param        $tableSoleName
     *
     * @param string $index
     * @param bool   $noArray
     * @param string $t
     * @param string $obj
     * @return string
     */
    public function getXcGetValues($tableName, $tableSoleName, $index = 'i', $noArray = false, $t = '', $obj = '')
    {
        $index        = '' !== $index ? $index : 'i';
        $ucfTableName = \ucfirst($tableName);
        if (!$noArray) {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}All[\${$index}]->getValues{$ucfTableName}();\n";
        } else {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}{$obj}->getValues{$ucfTableName}();\n";
        }

        return $ret;
    }

    /**
     * @public function getXcSetVarsObjects
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fields
     * @return string
     */
    public function getXcSetVarsObjects($moduleDirname, $tableName, $tableSoleName, $fields)
    {
        $axc           = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
        $ret           = '';
        $fieldMain     = '';
        $countUploader = 0;
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $axc->getAxcSetVarImageList($tableName, $fieldName, '', $countUploader);
                        $countUploader++;
                        break;
                    case 12:
                        $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, true, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 13:
                        $ret .= $axc->getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, '', $countUploader);
                        $countUploader++;
                        break;
                    case 14:
                        $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 15:
                        $ret .= $this->getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getXcSetVarObj($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     * @public function getXcSecurity
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getXcSecurity($tableName, $t = '')
    {
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $securityError   = $this->getXcXoopsSecurityErrors();
        $implode         = $pc->getPhpCodeImplode(',', $securityError);
        $content         = "{$t}\t" . $this->getXcRedirectHeader($tableName, '', 3, $implode, false, $t);
        $securityCheck   = $this->getXcXoopsSecurityCheck();

        return $pc->getPhpCodeConditions('!' . $securityCheck, '', '', $content, $t);
    }

    /**
     * @public function getXcInsertData
     * @param        $tableName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getXcInsertData($tableName, $language, $t = '')
    {
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $content       = "{$t}\t" . $this->getXcRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = $this->getXcHandlerInsert($tableName, $tableName, 'Obj');

        return $pc->getPhpCodeConditions($handlerInsert, '', '', $content, $t);
    }

    /**
     * @public function getXcRedirectHeader
     * @param        $directory
     * @param        $options
     * @param        $numb
     * @param        $var
     * @param bool   $isString
     * @param string $t
     * @return string
     */
    public function getXcRedirectHeader($directory, $options, $numb, $var, $isString = true, $t = '')
    {
        if (!$isString) {
            $ret = "{$t}\\redirect_header({$directory}, {$numb}, {$var});\n";
        } else {
            $ret = "{$t}\\redirect_header('{$directory}.php{$options}', {$numb}, {$var});\n";
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsConfirm
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $options
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, $options = 'delete', $t = '')
    {
        $stuOptions = \mb_strtoupper($options);
        $ccFieldId  = Modulebuilder\Files\CreateFile::getInstance()->getCamelCase($fieldId, false, true);
        $pc         = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc         = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $array   = "['ok' => 1, '{$fieldId}' => \${$ccFieldId}, 'op' => '{$options}']";
        $server  = $pc->getPhpCodeGlobalsVariables('REQUEST_URI', 'SERVER');
        $getVar  = $this->getXcGetVar('', $tableName . 'Obj', $fieldMain, true, '');
        $sprintf = $pc->getPhpCodeSprintf($language . 'FORM_SURE_' . $stuOptions, $getVar);
        $confirm = 'new Common\XoopsConfirm(' . "\n";
        $confirm .= $t . "\t" . $array . ",\n";
        $confirm .= $t . "\t" . $server . ",\n";
        $confirm .= $t . "\t" . $sprintf . ')';
        $ret     = $xc->getXcEqualsOperator('$xoopsconfirm', $confirm, '', $t);
        $ret     .= $xc->getXcEqualsOperator('$form', '$xoopsconfirm->getFormXoopsConfirm()', '', $t);
        $ret     .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);
        return $ret;
    }

    /**
     * @public function getXcHtmlErrors
     *
     * @param        $tableName
     * @param bool   $isParam
     * @param string $obj
     *
     * @param string $t
     * @return string
     */
    public function getXcHtmlErrors($tableName, $isParam = false, $obj = 'Obj', $t = '')
    {
        if ($isParam) {
            $ret = "\${$tableName}{$obj}->getHtmlErrors()";
        } else {
            $ret = "{$t}\${$tableName}{$obj} = \${$tableName}->getHtmlErrors();";
        }

        return $ret;
    }

    /**
     * @public function getXcGetForm
     *
     * @param        $left
     * @param        $tableName
     * @param string $obj
     *
     * @param string $t
     * @return string
     */
    public function getXcGetForm($left, $tableName, $obj = '', $t = '')
    {
        $ucfTableName = \ucfirst($tableName);

        return "{$t}\${$left} = \${$tableName}{$obj}->getForm{$ucfTableName}();\n";
    }

    /**
     * @public function getTopicGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $tableNameTopic
     * @param        $fieldNameParent
     * @param        $fieldNameTopic
     * @param string $t
     * @return string
     */
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $ret      = Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $paramGet = $this->getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret      .= $this->getXcHandlerGet($rpFieldName, $paramGet, '', $tableNameTopic . 'Handler', false, $t);
        $ret      .= $this->getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

        return $ret;
    }

    /**
     * @public function getUploadImageGetVar
     * @param        $lpFieldName
     * @param        $rpFieldName
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    /*
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= $this->getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pc->getPhpCodeTernaryOperator('uploadImage', "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);
        $ret .= $this->getXcEqualsOperator("${$lpFieldName}['{$rpFieldName}']", '$uploadImage', null, $t);

        return $ret;
    }
    */

    /**
     * @public function getXcSaveFieldId
     *
     * @param $fields
     *
     * @return string
     */
    public function getXcSaveFieldId($fields)
    {
        $fieldId = '';
        foreach (\array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldId;
    }

    /**
     * @public function getXcSaveFieldMain
     *
     * @param $fields
     *
     * @return string
     */
    public function getXcSaveFieldMain($fields)
    {
        $fieldMain = '';
        foreach (\array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldMain;
    }

    /**
     * @public function getXcSaveElements
     *
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fields
     *
     * @param string $t
     * @return string
     */
    public function getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t = '')
    {
        $axc           = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
        $xc            = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ret           = '';
        $fieldMain     = '';
        $countUploader = 0;
        $fieldLines    = '';
        foreach (\array_keys($fields) as $f) {

            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldType    = $fields[$f]->getVar('field_type');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $fieldLines .= $this->getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName, $t);
                        break;
                    case 10:
                        $fieldLines .= $axc->getAxcSetVarImageList($tableName, $fieldName, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 11:
                        $fieldLines .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 12:
                        $fieldLines .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, true, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 13:
                        $fieldLines .= $axc->getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 14:
                        $fieldLines .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 15:
                        $fieldLines .= $this->getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    case 17:
                        $fieldLines .= $axc->getAxcSetVarPassword($tableName, $fieldName, $t);
                        $countUploader++;
                        break;
                    case 21:
                        $fieldLines .= $this->getXcSetVarDateTime($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    default:
                        $fieldLines .= $axc->getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, $t);
                        break;
                }
            }
        }
        if ($countUploader > 0) {
            $ret .= $xc->getXcEqualsOperator('$uploaderErrors', "''", null, $t);
        }
        $ret .= $fieldLines;

        return $ret;
    }

    /**
     * @public function getXcPageNav
     * @param        $tableName
     *
     * @param string $t
     * @param string $paramStart
     * @param string $paramOp
     * @return string
     */
    public function getXcPageNav($tableName, $t = '', $paramStart = 'start', $paramOp = "'op=list&limit=' . \$limit")
    {
        $pc        = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc       = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ret       = $pc->getPhpCodeCommentLine('Display Navigation', null, $t);
        $condition = $pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/pagenav', true, false, 'require', $t . "\t");
        $condition .= $cxc->getClassXoopsPageNav('pagenav', $tableName . 'Count', 'limit', 'start', $paramStart, $paramOp, false, $t . "\t");
        $condition .= $this->getXcXoopsTplAssign('pagenav', '$pagenav->renderNav(4)', true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '$limit', $condition, false, $t);

        return $ret;
    }

    /* @public function getXcGetGlobal
     * @param        $globals
     *
     * @param string $t
     * @return string
     */
    public function getXcGetGlobal($globals, $t = '')
    {
        $ret    = $t . "global ";
        $detail = '';
        foreach ($globals as $global) {
            if ($detail !== '') {
                $detail .= ', ';
            }
            $detail .= '$' . $global;
        }
       $ret .= $detail . ";\n";

        return $ret;
    }

    /**
     * @public function getXcGetCriteriaCompo
     *
     * @param        $var
     * @param string $t
     *
     * @return string
     */
    public function getXcCriteriaCompo($var, $t = '')
    {
        return "{$t}\${$var} = new \CriteriaCompo();\n";
    }

    /**
     * @public function getXcCriteria
     *
     * @param        $var
     * @param        $param1
     * @param string $param2
     * @param string $param3
     * @param bool   $isParam
     * @param string $t
     *
     * @return string
     */
    public function getXcCriteria($var, $param1, $param2 = '', $param3 = '', $isParam = false, $t = '')
    {
        $params = ('' != $param2) ? ', ' . $param2 : '';
        $params .= ('' != $param3) ? ', ' . $param3 : '';

        if (false === $isParam) {
            $ret = "{$t}\${$var} = new \Criteria({$param1}{$params});\n";
        } else {
            $ret = "new \Criteria({$param1}{$params})";
        }

        return $ret;
    }

    /**
     * @public function getXcCriteriaAdd
     *
     * @param        $var
     * @param        $param
     * @param string $t
     * @param string $n
     * @param string $condition
     * @return string
     */
    public function getXcCriteriaAdd($var, $param, $t = '', $n = "\n", $condition = '')
    {
        if ('' !== $condition) {
            $condition = ", {$condition}";
        }
        return "{$t}\${$var}->add({$param}{$condition});{$n}";
    }

    /**
     * @public function getXcCriteriaSetStart
     *
     * @param        $var
     * @param        $start
     * @param string $t
     *
     * @param string $n
     * @return string
     */
    public function getXcCriteriaSetStart($var, $start, $t = '', $n = "\n")
    {
        return "{$t}\${$var}->setStart({$start});{$n}";
    }

    /**
     * @public function getXcCriteriaSetLimit
     *
     * @param        $var
     * @param        $limit
     * @param string $t
     *
     * @param string $n
     * @return string
     */
    public function getXcCriteriaSetLimit($var, $limit, $t = '', $n = "\n")
    {
        return "{$t}\${$var}->setLimit({$limit});{$n}";
    }

    /**
     * @public function getXcCriteriaSetSort
     *
     * @param        $var
     * @param        $sort
     * @param string $t
     *
     * @param string $n
     * @return string
     */
    public function getXcCriteriaSetSort($var, $sort, $t = '', $n = "\n")
    {
        return "{$t}\${$var}->setSort({$sort});{$n}";
    }

    /**
     * @public function getXcCriteriaSetOrder
     *
     * @param        $var
     * @param        $order
     * @param string $t
     *
     * @param string $n
     * @return string
     */
    public function getXcCriteriaSetOrder($var, $order, $t = '', $n = "\n")
    {
        return "{$t}\${$var}->setOrder({$order});{$n}";
    }

    /*************************************************************/
    /** Xoops form components */
    /*************************************************************/

    /**
     * @public function getXcXoopsFormGroupPerm
     * @param $varLeft
     * @param $formTitle
     * @param $moduleId
     * @param $permName
     * @param $permDesc
     * @param $filename
     * @param $t
     *
     * @return string
     */
    public function getXcXoopsFormGroupPerm($varLeft = '', $formTitle = '', $moduleId = '', $permName = '', $permDesc = '', $filename = '', $t = '')
    {
        return "{$t}\${$varLeft} = new \XoopsGroupPermForm({$formTitle}, {$moduleId}, {$permName}, {$permDesc}, {$filename});\n";
    }


    /**
     * @public function getXoopsFormSelectExtraOptions
     * @param string $varSelect
     * @param string $caption
     * @param string $var
     * @param array  $options
     * @param bool   $setExtra
     *
     * @param string $t
     * @return string
     */
    public function getXoopsFormSelectExtraOptions($varSelect = '', $caption = '', $var = '', $options = [], $setExtra = true, $t = '')
    {
        $ret = "{$t}\${$varSelect} = new \XoopsFormSelect({$caption}, '{$var}', \${$var});\n";
        if (false !== $setExtra) {
            $ret .= "{$t}\${$varSelect}->setExtra('{$setExtra}');\n";
        }
        foreach ($options as $key => $value) {
            $ret .= "{$t}\${$varSelect}->addOption('{$key}', {$value});\n";
        }

        return $ret;
    }


    /*************************************************************/
    /** special components */
    /*************************************************************/
    /**
     * @public function getXcGetConstants
     * @param null
     * @return string
     */
    public function getXcGetConstants($const)
    {
        $ret = "Constants::{$const}";
        return $ret;
    }

    /*************************************************************/
    /** other Xoops components */
    /*************************************************************/


    /**
     * @public function getXcXoopsCPHeader
     * @param null
     * @return string
     */
    public function getXcXoopsCPHeader()
    {
        return "xoops_cp_header();\n";
    }

    /**
     * @public function getXcXoopsCPFooter
     * @param null
     * @return string
     */
    public function getXcXoopsCPFooter()
    {
        return "xoops_cp_footer();\n";
    }

    /**
     * @public function getXcXoopsLoad
     *
     * @param $var
     * @param $t
     * @return string
     */
    public function getXcXoopsLoad($var = '', $t = '')
    {
        return "{$t}\xoops_load('{$var}');\n";
    }

    /**
     * @public function getXcXoopsLoadLanguage
     *
     * @param $lang
     * @param $t
     * @param $domain
     *
     * @return string
     */
    public function getXcXoopsLoadLanguage($lang, $t = '', $domain = '')
    {
        if ('' === $domain) {
            return "{$t}\xoops_loadLanguage('{$lang}');\n";
        }

        return "{$t}\xoops_loadLanguage('{$lang}', '{$domain}');\n";
    }

    /**
     * @public function getXcXoopsCaptcha
     * @param $t
     * @return string
     */
    public function getXcXoopsCaptcha($t = '')
    {
        return "{$t}\$xoopsCaptcha = \XoopsCaptcha::getInstance();\n";
    }

    /**
     * @public function getXcXoopsListImgListArray
     * @param $return
     * @param $var
     * @param $t
     *
     * @return string
     */
    public function getXcXoopsListImgListArray($return, $var, $t = '')
    {
        return "{$t}\${$return} = \XoopsLists::getImgListAsArray( {$var} );\n";
    }

    /**
     * @public function getXcXoopsListLangList
     * @param $return
     * @param string $t
     *
     * @return string
     */
    public function getXcXoopsListLangList($return, $t = '')
    {
        return "{$t}\${$return} = \XoopsLists::getLangList();\n";
    }

    /**
     * @public function getXcXoopsListCountryList
     * @param $return
     * @param string $t
     *
     * @return string
     */
    public function getXcXoopsListCountryList($return, $t = '')
    {
        return "{$t}\${$return} = \XoopsLists::getCountryList();\n";
    }

    /**
     * @public function getXcXoopsUserUnameFromId
     * @param        $left
     * @param        $value
     * @param string $t
     *
     * @return string
     */
    public function getXcXoopsUserUnameFromId($left, $value, $t = '')
    {
        return "{$t}\${$left} = \XoopsUser::getUnameFromId({$value});\n";
    }

    /**
     * @public function getXcXoopsTplAssign
     *
     * @param        $tplString
     * @param        $phpRender
     * @param bool   $leftIsString
     * @param string $t
     * @param string $tpl
     * @return string
     */
    public function getXcXoopsTplAssign($tplString, $phpRender, $leftIsString = true, $t = '', $tpl = '')
    {
        $assign = "{$t}\$GLOBALS['xoopsTpl']->assign(";
        if ('' !== $tpl) {
            $assign = "{$t}\${$tpl}->assign(";
        }

        if (false === $leftIsString) {
            $ret = $assign . "{$tplString}, {$phpRender});\n";
        } else {
            $ret = $assign . "'{$tplString}', {$phpRender});\n";
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsTplAppend
     *
     * @param        $tplString
     * @param        $phpRender
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsTplAppend($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
    }

    /**
     * @public function getXcXoopsTplAppendByRef
     *
     * @param        $tplString
     * @param        $phpRender
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsTplAppendByRef($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
    }

    /**
     * @public function getXcXoopsTplDisplay
     *
     * @param string $displayTpl
     * @param string $t
     * @param bool   $usedoublequotes
     * @return string
     */
    public function getXcXoopsTplDisplay($displayTpl = '{$templateMain}', $t = '', $usedoublequotes = true)
    {
        if ($usedoublequotes) {
            return "{$t}\$GLOBALS['xoopsTpl']->display(\"db:{$displayTpl}\");\n";
        }

        return "{$t}\$GLOBALS['xoopsTpl']->display('db:" . $displayTpl . "');\n";
    }

    /**
     * @public function getXcXoopsPath
     *
     * @param        $directory
     * @param        $filename
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsPath($directory, $filename, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php');\n";
        } else {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php')";
        }

        return $ret;
    }

    /**
     * @public function getXcXoopsModuleGetInfo
     *
     * @param        $left
     * @param        $string
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getXcXoopsModuleGetInfo($left, $string, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$left} = \$GLOBALS['xoopsModule']->getInfo('{$string}');\n";
        } else {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}')";
        }

        return $ret;
    }


    /**
     * @public function getXcXoThemeAddStylesheet
     * @param string $style
     *
     * @param string $t
     * @param bool $isString
     * @return string
     */
    public function getXcXoThemeAddStylesheet($style = 'style', $t = '', $isString = true)
    {
        $ret = "{$t}\$GLOBALS['xoTheme']->addStylesheet(";
        if ($isString) {
            $ret .= '$';
        }
        $ret .= "{$style}, null);\n";
        return $ret;
    }

    /**
     * @public function getXcXoopsSecurityCheck
     * @param $denial
     * @return bool
     */
    public function getXcXoopsSecurityCheck($denial = '')
    {
        return "{$denial}\$GLOBALS['xoopsSecurity']->check()";
    }

    /**
     * @public function getXcXoopsSecurityErrors
     * @param null
     * @return string
     */
    public function getXcXoopsSecurityErrors()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     * @public function getXcXoopsHandler
     * @param        $left
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getXcXoopsHandler($left, $t = '', $n = "\n")
    {
        return "{$t}\${$left}Handler = \xoops_getHandler('{$left}');{$n}";
    }


    /*************************************************************/
    /** common components for user and admin pages */
    /*************************************************************/

    /**
     * @public  function getXcCommonPagesEdit
     * @param        $tableName
     * @param        $ccFieldId
     * @param string $t
     * @return string
     */
    public function getXcCommonPagesEdit($tableName, $ccFieldId, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Get Form', null, "\t\t");
        $ret .= $xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @public  function getXcCommonPagesNew
     * @param        $tableName
     * @param string $t
     * @return string
     */
    public function getXcCommonPagesNew($tableName, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Form Create', null, $t);
        $ret .= $xc->getXcHandlerCreateObj($tableName, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @public function getXcCommonPagesDelete
     * @param        $language
     * @param        $tableName
     * @param $tableSoleName
     * @param        $fieldId
     * @param        $fieldMain
     * @param $tableNotifications
     * @param string $t
     * @param bool $admin
     * @return string
     */
    public function getXcCommonPagesDelete($language, $tableName, $tableSoleName, $fieldId, $fieldMain, $tableNotifications, $t = '', $admin = false)
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cf = Modulebuilder\Files\CreateFile::getInstance();

        $ccFieldId    = $cf->getCamelCase($fieldId, false, true);
        $ccFieldMain  = $cf->getCamelCase($fieldMain, false, true);

        $ret                  = $xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $ret                  .= $xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t);
        $reqOk                = "_REQUEST['ok']";
        $isset                = $pc->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck   = $xc->getXcXoopsSecurityCheck();
        $xoopsSecurityErrors  = $xc->getXcXoopsSecurityErrors();
        $implode              = $pc->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t\t");
        $delete               = $xc->getXcHandlerDelete($tableName, $tableName, 'Obj', 'Handler');
        $condition            = $pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t . "\t");
        $contInsert = '';
        if (!$admin && 1 == $tableNotifications) {
            $contInsert .= $pc->getPhpCodeCommentLine('Event delete notification', null, $t . "\t\t");
            $contInsert .= $pc->getPhpCodeArray('tags', [], false, $t . "\t\t");
            $contInsert .= $xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t . "\t\t");
            $contInsert .= $xc->getXcXoopsHandler('notification', $t . "\t\t");
            $contInsert .= $cf->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_delete', \$tags);", $t . "\t\t");
            $contInsert .= $cf->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \${$ccFieldId}, '{$tableSoleName}_delete', \$tags);", $t . "\t\t");
        }
        $contInsert   .= $xc->getXcRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK", true, $t . "\t\t");
        $htmlErrors   = $xc->getXcHtmlErrors($tableName, true);
        $internalElse = $xc->getXcXoopsTplAssign('error', $htmlErrors, true, $t . "\t\t");
        $condition    .= $pc->getPhpCodeConditions($delete, '', '', $contInsert, $internalElse, $t . "\t");
        $mainElse     = $xc->getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'delete', $t . "\t");
        $ret          .= $pc->getPhpCodeConditions($isset, ' && ', "1 == \${$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }
}

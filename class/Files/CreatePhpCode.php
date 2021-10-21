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
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */

/**
 * Class CreatePhpCode.
 */
class CreatePhpCode
{
    /**
     * @static function getInstance
     * @param null
     * @return CreatePhpCode
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
     * @public function getPhpCodeCommentLine
     * @param        $comment
     * @param        $var
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getPhpCodeCommentLine($comment = null, $var = null, $t = '', $n = "\n")
    {
        $value = !empty($var) ? ' ' . $var : '';
        $ret   = "{$t}// {$comment}{$value}{$n}";

        return $ret;
    }

    /**
     * @public function getPhpCodeCommentMultiLine
     * @param array  $multiLine
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeCommentMultiLine($multiLine = [], $t = '')
    {
        $values = !empty($multiLine) ? $multiLine : [];

        $ret = "\n{$t}/**\n";
        foreach ($values as $string => $value) {
            if ('' === $string && '' === $value) {
                $ret .= "{$t} *\n";
            } else {
                if ('' === $value) {
                    $ret .= "{$t} * {$string}\n";
                } else {
                    $ret .= "{$t} * {$string} {$value}\n";
                }
            }
        }
        $ret .= "{$t} */\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeDefine
     * @param        $left
     * @param        $right
     *
     * @param string $t
     * @param bool   $leftstr
     * @return string
     */
    public function getPhpCodeDefine($left, $right, $t = '', $leftstr = true)
    {
        $ret = "{$t}\define(";
        if ($leftstr) {
            $ret .= "'{$left}'";
        } else {
            $ret .= "{$left}";
        }
        $ret .= ", {$right});\n";
        return $ret;
    }

    /**
     * @public function getPhpCodeDefine
     * @param $left
     * @param $right
     *
     * @return string
     */
    public function getPhpCodeDefined($left = 'XOOPS_ROOT_PATH', $right = 'Restricted access')
    {
        return "\defined('{$left}') || die('{$right}');\n";
    }

    /**
     * @public function getPhpCodeGlobals
     * @param $var
     * @param $value
     *
     * @return string
     */
    public function getPhpCodeGlobals($var, $value = '')
    {
        if ('' != $value) {
            $ret = "\$GLOBALS['{$var}'] = \${$value};\n";
        } else {
            $ret = "\$GLOBALS['{$var}']";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeGlobalsVariables
     * @param $var
     * @param $type
     *
     * @return string
     */
    public function getPhpCodeGlobalsVariables($var = null, $type = 'REQUEST')
    {
        $type = \mb_strtoupper($type);
        switch ($type) {
            case 'GET':
                $ret = "\$_GET['{$var}']";
                break;
            case 'POST':
                $ret = "\$_POST['{$var}']";
                break;
            case 'FILES':
                $ret = "\$_FILES['{$var}']";
                break;
            case 'COOKIE':
                $ret = "\$_COOKIE['{$var}']";
                break;
            case 'ENV':
                $ret = "\$_ENV['{$var}']";
                break;
            case 'SERVER':
                $ret = "\$_SERVER['{$var}']";
                break;
            default:
                $ret = "\$_REQUEST['{$var}']";
                break;
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeRemoveCarriageReturn
     * @param        $string
     *
     * @param string $n
     * @param string $t
     * @return string
     */
    public function getPhpCodeRemoveCarriageReturn($string, $n = "\n", $t = "\r")
    {
        return \str_replace([(string)$n, (string)$t], '', $string);
    }

    /**
     * @public function getPhpCodeFileExists
     * @param $filename
     *
     * @return string
     */
    public function getPhpCodeFileExists($filename)
    {
        return "\\file_exists({$filename})";
    }

    /**
     * @public function getPhpCodeIncludeDir
     * @param        $directory
     * @param        $filename
     * @param bool   $once
     * @param bool   $isPath
     *
     * @param string $type
     * @param string $t
     * @return string
     */
    public function getPhpCodeIncludeDir($directory = null, $filename = null, $once = false, $isPath = false, $type = 'require', $t = '')
    {
        if ('' === $type) {
            $type = 'require';
        }
        if (false === $once) {
            if (!$isPath) {
                $ret = "{$t}{$type} {$directory} . '/{$filename}.php';\n";
            } else {
                $ret = "{$t}{$type} {$directory};\n";
            }
        } else {
            if (!$isPath) {
                $ret = "{$t}{$type}_once {$directory} . '/{$filename}.php';\n";
            } else {
                $ret = "{$t}{$type}_once {$directory};\n";
            }
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeTernaryOperator
     * @param $return
     * @param $condition
     * @param $one
     * @param $two
     * @param $t - Indentation
     *
     * @return string
     */
    public function getPhpCodeTernaryOperator($return, $condition, $one, $two, $t = '')
    {
        $ret = "{$t}\${$return} = {$condition} ?";
        if ('' != $one) {
            //not shorthand/elvis
            $ret .= " {$one} ";
        }
        $ret .= ": {$two};\n";
        return $ret;
    }

    /**
     * @public function getPhpCodeClass
     * @param      $name
     * @param      $content
     * @param      $extends
     * @param      $type
     *
     * @param null $implements
     * @return string
     */
    public function getPhpCodeClass($name = null, $content = null, $extends = null, $type = null, $implements = null)
    {
        $typ = (null != $type) ? "{$type} " : '';
        $ext = (null != $extends) ? " extends {$extends}" : '';
        $imp = (null != $implements) ? " implements {$implements}" : '';
        $ret = "{$typ}class {$name}{$ext}{$imp}\n";
        $ret .= '{';
        $ret .= $content;
        $ret .= "}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeClass
     * @param $type
     * @param $name
     * @param $assign
     * @param $t - Indentation
     *
     * @return string
     */
    public function getPhpCodeVariableClass($type = 'private', $name = null, $assign = 'null', $t = '')
    {
        return "{$t}{$type} \${$name} = {$assign};\n";
    }

    /**
     * @public function getPhpCodeInstance
     * @param $name
     * @param $content
     * @param $extends
     * @param $type
     *
     * @return string
     */
    public function getPhpCodeInterface($name = null, $content = null, $extends = null, $type = null)
    {
        $typ = (null != $type) ? "{$type} " : '';
        $ext = (null != $extends) ? " extends {$extends}" : '';
        $ret = "{$typ}interface {$name}{$ext}\n";
        $ret .= '{';
        $ret .= $content;
        $ret .= "}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeFunction
     * @param        $name
     * @param        $params
     * @param        $content
     * @param        $method
     * @param bool   $isRef
     * @param string $t - Indentation
     * @return string
     */
    public function getPhpCodeFunction($name = null, $params = null, $content = null, $method = null, $isRef = false, $t = '')
    {
        $inClass = (null != $method) ? $method : '';
        $ref     = (false !== $isRef) ? '&' : '';
        $ret     = "{$t}{$inClass}function {$ref}{$name}({$params})\n";
        $ret     .= "{$t}{\n";
        $ret     .= $content;
        $ret     .= "{$t}}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeConditions
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     * @param string $t - Indentation
     *
     * @param string $conditionElse
     * @return string
     */
    public function getPhpCodeConditions($condition = null, $operator = null, $type = null, $contentIf = null, $contentElse = false, $t = '', $conditionElse = '')
    {
        if ('==' === \trim($operator) || '===' === \trim($operator) || '!=' === \trim($operator) || '!==' === \trim($operator)) {
            //yoda conditions
            $left  = $type;
            $right = $condition;
        } else {
            $left  = $condition;
            $right = $type;
        }
        if (false === $contentElse) {
            $ret = "{$t}if ({$left}{$operator}{$right}) {\n";
            $ret .= $contentIf;
            $ret .= "{$t}}\n";
        } else {
            $ret = "{$t}if ({$left}{$operator}{$right}) {\n";
            $ret .= $contentIf;
            if ('' !== $conditionElse) {
                $ret .= "{$t}} elseif ({$conditionElse}) {\n";
            } else {
                $ret .= "{$t}} else {\n";
            }

            $ret .= $contentElse;
            $ret .= "{$t}}\n";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeForeach
     * @param string      $array
     * @param bool|string $arrayKey
     * @param bool|string $key
     * @param bool|string $value
     * @param string      $content
     *
     * @param string      $t
     * @return string
     */
    public function getPhpCodeForeach($array, $arrayKey = false, $key = false, $value = false, $content = null, $t = '')
    {
        $vars  = '';
        $value = '' !== $value ? $value : 'i';
        if ((false === $arrayKey) && (false === $key)) {
            $vars = "\${$array} as \${$value}";
        } elseif ((false === $arrayKey) && (false !== $key)) {
            $vars = "\${$array} as \${$key} => \${$value}";
        } elseif ((false !== $arrayKey) && (false === $key)) {
            $vars = "\array_keys(\${$array}) as \${$value}";
        }

        $ret = "{$t}foreach ({$vars}) {\n";
        $ret .= "{$content}";
        $ret .= "{$t}}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeFor
     * @param        $var
     * @param        $content
     * @param        $value
     * @param        $initVal
     * @param        $operator
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeFor($var = null, $content = null, $value = null, $initVal = null, $operator = null, $t = '')
    {
        $ret = "{$t}for (\${$var} = {$initVal}; \${$var} {$operator} \${$value}; \${$var}++) {\n";
        $ret .= "{$content}";
        $ret .= "{$t}}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeWhile
     * @param $var
     * @param $content
     * @param $value
     * @param $operator
     * @param $t
     *
     * @return string
     */
    public function getPhpCodeWhile($var = null, $content = null, $value = null, $operator = null, $t = '')
    {
        $ret = "{$t}while (\${$var} {$operator} {$value}) {\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeSwitch
     *
     * @param        $op
     * @param        $content
     * @param string $t
     *
     * @param bool   $isParam
     * @return string
     */
    public function getPhpCodeSwitch($op = null, $content = null, $t = '', $isParam = true)
    {
        $value = $isParam ? "\${$op}" : $op;
        $ret   = "{$t}switch ({$value}) {\n";
        $ret   .= $content;
        $ret   .= "{$t}}\n";

        return $ret;
    }

    /**
     * @public function getPhpCodeCaseSwitch
     *
     * @param array  $cases
     * @param bool   $defaultAfterCase
     * @param bool   $default
     * @param string $t
     *
     * @param bool   $isConst
     * @return string
     */
    public function getPhpCodeCaseSwitch($cases = [], $defaultAfterCase = false, $default = false, $t = '', $isConst = false)
    {
        $ret = '';
        $def = "{$t}default:\n";
        foreach ($cases as $case => $value) {
            $case = $isConst || !\is_string($case) ? $case : "'{$case}'";
            if (empty($value)) {
                $ret .= "{$t}case {$case}:\n";
            } elseif (!empty($case)) {
                $ret .= "{$t}case {$case}:\n";
                if (false !== $defaultAfterCase) {
                    $ret .= $def;
                }
                if (\is_array($value)) {
                    foreach ($value as $content) {
                        $ret .= "{$content}";
                    }
                }
                $ret              .= "{$t}\tbreak;\n";
                $defaultAfterCase = false;
            }
        }
        if (false !== $default) {
            $ret .= $def;
            $ret .= "{$t}{$default}\n";
            $ret .= "{$t}\tbreak;\n";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeIsset
     * @param $var
     * @return string
     */
    public function getPhpCodeIsset($var)
    {
        return "isset(\${$var})";
    }

    /**
     * @public function getPhpCodeUnset
     * @param string $var
     * @param string $t
     * @return string
     */
    public function getPhpCodeUnset($var = '', $t = '')
    {
        return "{$t}unset(\${$var});\n";
    }

    /**
     * @public function getPhpCodeIsDir
     * @param $var
     * @return string
     */
    public function getPhpCodeIsDir($var)
    {
        return "\is_dir({$var})";
    }

    /**
     * @public function getPhpCodeImplode
     * @param $left
     * @param $right
     * @return string
     */
    public function getPhpCodeImplode($left, $right)
    {
        return "\implode('{$left}', {$right})";
    }

    /**
     * @public function getPhpCodeExplode
     * @param $left
     * @param $right
     * @return string
     */
    public function getPhpCodeExplode($left, $right)
    {
        return "\\explode('{$left}', {$right})";
    }

    /**
     * @public function getPhpCodeChmod
     * @param        $var
     * @param string $perm
     * @param string $t
     * @return string
     */
    public function getPhpCodeChmod($var, $perm = '0777', $t = '')
    {
        return "{$t}chmod(\${$var}, {$perm});\n";
    }

    /**
     * @public function getPhpCodeMkdir
     * @param        $var
     * @param string $perm
     * @param string $t
     * @return string
     */
    public function getPhpCodeMkdir($var, $perm = '0777', $t = '')
    {
        return "{$t}\mkdir(\${$var}, {$perm});\n";
    }

    /**
     * @public function getPhpCodeCopy
     * @param        $file
     * @param string $newfile
     * @param string $t
     * @return string
     */
    public function getPhpCodeCopy($file, $newfile = '', $t = '')
    {
        return "{$t}\copy({$file}, {$newfile});\n";
    }

    /**
     * @public function getPhpCodeArray
     * @param        $var
     * @param        $array
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeArray($var, $array = null, $isParam = false, $t = "\t\t")
    {
        $retArray = [];
        if (\is_array($array) && !empty($array)) {
            foreach ($array as $k => $v) {
                if (is_numeric($k)) {
                    $retArray[] = $v;
                } else {
                    $retArray[] = "{$k} => {$v}";
                }
            }
            $arrayContent = \implode(', ', $retArray);
        } else {
            $arrayContent = '';
        }
        unset($retArray);

        if (!$isParam) {
            $ret = "{$t}\${$var} = [{$arrayContent}];\n";
        } else {
            $ret = "[{$array}]";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeArrayType
     * @param        $var
     * @param        $type
     * @param        $left
     * @param        $right
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeArrayType($var, $type, $left, $right = null, $isParam = false, $t = "\t\t")
    {
        $vars = (null != $right) ? "\${$left}, {$right}" : "\${$left}";
        if (!$isParam) {
            $ret = "{$t}\${$var}[] = \array_{$type}({$vars});\n";
        } else {
            $ret = "\array_{$type}({$vars})";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeArrayType
     * @param        $var
     * @param string $t
     * @return string
     */
    public function getPhpCodeArrayShift($var, $t = '')
    {
        return "{$t}\array_shift({$var});\n";
    }

    /**
     * @public function getPhpCodeSprintf
     * @param $left
     * @param $right
     * @return string
     */
    public function getPhpCodeSprintf($left, $right)
    {
        return "\sprintf({$left}, {$right})";
    }

    /**
     * @public function getPhpCodeEmpty
     * @param $var
     * @return string
     */
    public function getPhpCodeEmpty($var)
    {
        return "empty({$var})";
    }

    /**
     * @public function getPhpCodeHeader
     * @param $var
     * @return string
     */
    public function getPhpCodeHeader($var)
    {
        return "header({$var})";
    }

    /**
     * @public function getPhpCodeRawurlencode
     * @param $var
     * @return string
     */
    public function getPhpCodeRawurlencode($var)
    {
        return "rawurlencode({$var})";
    }

    /**
     * @public function getPhpCodePregFunzions
     * @param        $var
     * @param        $exp
     * @param        $str
     * @param        $val
     * @param string $type
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodePregFunzions($var, $exp, $str, $val, $type = 'match', $isParam = false, $t = "\t")
    {
        $pregFunz = "\preg_{$type}('";
        if (!$isParam) {
            $ret = "{$t}\${$var} = {$pregFunz}{$exp}', '{$str}', {$val});\n";
        } else {
            $ret = "{$pregFunz}{$exp}', '{$str}', {$val})";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeStrType
     * @param        $left
     * @param        $var
     * @param        $str
     * @param        $value
     * @param string $type
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeStrType($left, $var, $str, $value, $type = 'replace', $isParam = false, $t = "\t")
    {
        $strType = "\str_{$type}('";
        if (!$isParam) {
            $ret = "{$t}\${$left} = {$strType}{$var}', '{$str}', {$value});\n";
        } else {
            $ret = "{$strType}{$var}', '{$str}', {$value})";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeStripTags
     * @param        $left
     * @param        $value
     * @param bool   $isParam
     *
     * @param string $t
     * @return string
     */
    public function getPhpCodeStripTags($left, $value, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$left} = \strip_tags({$value});\n";
        } else {
            $ret = "\strip_tags({$value})";
        }

        return $ret;
    }

    /**
     * @public function getPhpCodeHtmlentities
     * @param $entitiesVar
     * @param $entitiesQuote
     * @return string
     */
    public function getPhpCodeHtmlentities($entitiesVar, $entitiesQuote = false)
    {
        $entitiesVar = (false !== $entitiesQuote) ? $entitiesVar . ', ' . $entitiesQuote : $entitiesVar;
        $entities    = "htmlentities({$entitiesVar})";

        return $entities;
    }

    /**
     * @public function getPhpCodeHtmlspecialchars
     * @param $specialVar
     * @param $specialQuote
     * @return string
     */
    public function getPhpCodeHtmlspecialchars($specialVar, $specialQuote = false)
    {
        $specialVar   = (false !== $specialQuote) ? $specialVar . ', ' . $specialQuote : $specialVar;
        $specialchars = "htmlspecialchars({$specialVar})";

        return $specialchars;
    }

    /**
     * @public function getPhpCodeNamespace
     * @param        $dimensions
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getPhpCodeNamespace($dimensions, $t = '', $n = "\n\n")
    {
        $ret = "\n{$t}namespace ";
        foreach ($dimensions as $key => $dim) {
            if ($key > 0) {
                $ucfDim = \ucfirst($dim);
                $ret    .= "\\{$ucfDim}";
            } else {
                $ret .= "{$dim}";
            }
        }
        $ret .= ";" . $n;

        return $ret;
    }

    /**
     * @public function getPhpCodeUseNamespace
     * @param        $dimensions
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getPhpCodeUseNamespace($dimensions, $t = '', $n = "\n\n")
    {
        $ret = "\n{$t}use ";
        foreach ($dimensions as $key => $dim) {
            if ($key > 0) {
                $ucfDim = \ucfirst($dim);
                $ret    .= "\\{$ucfDim}";
            } else {
                $ret .= "{$dim}";
            }
        }
        $ret .= ";" . $n;

        return $ret;
    }

    /**
     * @public function getPhpCodeBlankLine
     *
     * @return string
     */
    public function getPhpCodeBlankLine()
    {
        return "\n";
    }

    /**
     * @public function getPhpCodeConstant
     *
     * @param        $const
     * @param        $value
     * @param string $t
     * @param string $type
     * @return string
     */
    public function getPhpCodeConstant($const, $value, $t = '', $type = 'const')
    {
        return "{$t}{$type} {$const} = {$value};\n";
    }

    /**
     * @public function getPhpCodeTriggerError
     *
     * @param        $msg
     * @param        $type
     * @param string $t
     * @return string
     */
    public function getPhpCodeTriggerError($msg, $type, $t = '')
    {
        $ret = "{$t}\\trigger_error($msg, {$type});\n";
        return $ret;
    }
}

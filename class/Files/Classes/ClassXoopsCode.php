<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Classes;

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
 */

/**
 * Class ClassXoopsCode.
 */
class ClassXoopsCode
{
    /*
    *  @static function getInstance
     *
     * @return ClassXoopsCode
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
     * @public function getClassAdd
     *
     * @param string $paramLeft
     * @param string $paramRight
     * @param string $var
     * @param string $t
     *
     * @return string
     */
    public function getClassInitVar(string $paramLeft = '', string $paramRight = '', string $var = 'this', string $t = "\t\t")
    {
        $stuParamRight = \mb_strtoupper($paramRight);

        return "{$t}\${$var}->initVar('{$paramLeft}', \XOBJ_DTYPE_{$stuParamRight});\n";
    }

    /**
     * @public function getClassXoopsPageNav
     *
     * @param        $var
     * @param        $param1
     * @param null   $param2
     * @param null   $param3
     * @param null   $param4
     * @param null   $param5
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsPageNav($var, $param1, $param2 = null, $param3 = null, $param4 = null, $param5 = null, bool $isParam = false, string $t = '')
    {
        $xPageNav = 'new \XoopsPageNav(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$xPageNav}\${$param1}, \${$param2}, \${$param3}, '{$param4}', {$param5});\n";
        } else {
            $ret = "{$xPageNav}\${$param1}, \${$param2}, \${$param3}, '{$param4}', {$param5})";
        }

        return $ret;
    }

    /**
     * @public function getXoopsSimpleForm
     *
     * @param string $left
     * @param string $element
     * @param string $elementsContent
     * @param string $caption
     * @param string $var
     * @param string $filename
     * @param string $type
     * @param string $t
     *
     * @return string
     */
    public function getXoopsSimpleForm(string $left = '', string $element = '', string $elementsContent = '', string $caption = '', string $var = '', string $filename = '', string $type = 'post', string $t = '')
    {
        $ret = "{$t}\${$left} = new \XoopsSimpleForm({$caption}, '{$var}', '{$filename}.php', '{$type}');\n";
        if (!empty($elementsContent)) {
            $ret .= $elementsContent;
        }
        $ret .= "{$t}\${$left}->addElement(\${$element});\n";
        $ret .= "{$t}\${$left}->display();\n";

        return $ret;
    }

    /**
     * @public function getClassXoopsThemeForm
     *
     * @param        $var
     * @param        $param1
     * @param null   $param2
     * @param null   $param3
     * @param null   $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsThemeForm($var, $param1, $param2 = null, $param3 = null, $param4 = null, bool $isParam = false, string $t = "\t\t")
    {
        $themeForm = 'new \XoopsThemeForm(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$themeForm}\${$param1}, '{$param2}', \${$param3}, '{$param4}', true);\n";
        } else {
            $ret = "{$themeForm}\${$param1}, '{$param2}', \${$param3}, '{$param4}', true)";
        }

        return $ret;
    }

    /**
     * @public function XoopsFormElementTray
     *
     * @param        $var
     * @param        $param1
     * @param string $param2
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormElementTray($var, $param1, string $param2 = '', string $t = "\t\t")
    {
        return "{$t}\${$var} = new \XoopsFormElementTray({$param1}, '{$param2}');\n";
    }

    /**
     * @public function getClassXoopsFormLabel
     *
     * @param        $var
     * @param string $param1
     * @param null   $param2
     * @param bool $isParam
     * @param string $t
     *
     * @param bool $useParam
     * @return string
     */
    public function getClassXoopsFormLabel($var, string $param1 = '', $param2 = null, bool $isParam = false, string $t = "\t\t", bool $useParam = false)
    {
        $label = 'new \XoopsFormLabel(';
        if (false === $useParam) {
            $params = null != $param2 ? "{$param1}, {$param2}" : $param1;
        } else {
            $params = null != $param2 ? "{$param1}, \${$param2}" : $param1;
        }
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$label}{$params});\n";
        } else {
            $ret = "{$label}{$params})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormFile
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormFile($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $file = 'new \XoopsFormFile(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$file}{$param1}, '{$param2}', {$param3});\n";
        } else {
            $ret = "{$file}{$param1}, '{$param2}', {$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormHidden
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param bool $isForm
     * @param bool $isParam
     * @param string $t
     *
     * @param bool $useParam
     * @return string
     */
    public function getClassXoopsFormHidden($var, $param1, $param2, bool $isForm = false, bool $isParam = false, string $t = "\t\t", bool $useParam = false)
    {
        $hidden       = 'new \XoopsFormHidden(';
        $getVarHidden = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param2, true);
        $ret          = '';
        if (false === $isParam) {
            $ret .= "{$t}\${$var} = {$hidden}{$param1}, {$getVarHidden});\n";
        } else {
            if (false === $isForm) {
                $ret .= "{$hidden}{$param1}, {$param2})";
            } else {
                if (false === $useParam) {
                    $ret .= "{$hidden}'{$param1}', '{$param2}')";
                } else {
                    $ret .= "{$hidden}'{$param1}', \${$param2})";
                }
            }
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormText
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param int $param3
     * @param int $param4
     * @param        $param5
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormText($var, $param1, $param2, int $param3, int $param4, $param5, bool $isParam = false, string $t = "\t\t")
    {
        $text = 'new \XoopsFormText(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$text}{$param1}, '{$param2}', {$param3}, {$param4}, \${$param5});\n";
        } else {
            $ret = "{$text}{$param1}, '{$param2}', {$param3}, {$param4}, \${$param5})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormTextArea
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param        $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormTextArea($var, $param1, $param2, $param3, $param4, bool $isParam = false, string $t = "\t\t")
    {
        $area           = 'new \XoopsFormTextArea(';
        $getVarTextArea = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param2, true, '', ", 'e'");
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$area}{$param1}, '{$param2}', {$getVarTextArea}, {$param3}, {$param4});\n";
        } else {
            $ret = "{$area}{$param1}, '{$param2}', {$getVarTextArea}, {$param3}, {$param4})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormColorPicker
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormColorPicker($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $picker = 'new \XoopsFormColorPicker(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$picker}{$param1}, '{$param2}', {$param3});\n";
        } else {
            $ret = "{$picker}{$param1}, '{$param2}', {$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormSelectUser
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param string $param3
     * @param        $ccFieldName
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormSelectUser($var, $param1, $param2, string $param3, $ccFieldName, bool $isParam = false, string $t = "\t\t")
    {
        $user = 'new \XoopsFormSelectUser(';
        //$getVarSelectUser = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param4, true);
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$user}{$param1}, '{$param2}', {$param3}, {$ccFieldName});\n";
        } else {
            $ret = "{$user}{$param1}, '{$param2}', {$param3}, {$ccFieldName})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormTextDateSelect
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param string $param3
     * @param        $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormTextDateSelect($var, $param1, $param2, string $param3, $param4, bool $isParam = false, string $t = "\t\t")
    {
        $tdate                = 'new \XoopsFormTextDateSelect(';
        $getVarTextDateSelect = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param3, true);
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$tdate}{$param1}, '{$param2}', '', {$getVarTextDateSelect});\n";
        } else {
            $ret = "{$tdate}{$param1}, '{$param2}', '', \${$param4})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormDateTime
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param string $param3
     * @param        $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormDateTime($var, $param1, $param2, string $param3, $param4, bool $isParam = false, string $t = "\t\t")
    {
        $tdate                = 'new \XoopsFormDateTime(';
        $getVarTextDateSelect = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param3, true);
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$tdate}{$param1}, '{$param2}', '', {$getVarTextDateSelect});\n";
        } else {
            $ret = "{$tdate}{$param1}, '{$param2}', '', \${$param4})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormEditor
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormEditor($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $editor = 'new \XoopsFormEditor(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$editor}{$param1}, '{$param2}', \${$param3});\n";
        } else {
            $ret = "{$editor}{$param1}, '{$param2}', \${$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormCheckBox
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormCheckBox($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $checkBox = 'new \XoopsFormCheckBox(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$checkBox}{$param1}, '{$param2}', {$param3});\n";
        } else {
            $ret = "{$checkBox}{$param1}, '{$param2}', {$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormRadioYN
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormRadioYN($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $radioYN = 'new \XoopsFormRadioYN(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$radioYN}{$param1}, '{$param2}', \${$param3});\n";
        } else {
            $ret = "{$radioYN}{$param1}, '{$param2}', \${$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormRadioYN
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormRadio($var, $param1, $param2, $param3, bool $isParam = false, string $t = "\t\t")
    {
        $radioYN = 'new \XoopsFormRadio(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$radioYN}{$param1}, '{$param2}', \${$param3});\n";
        } else {
            $ret = "{$radioYN}{$param1}, '{$param2}', \${$param3})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormSelect
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param null   $param4
     * @param null   $param5
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormSelect($var, $param1, $param2, $param3, $param4 = null, $param5 = null, bool $isParam = false, string $t = "\t\t")
    {
        $otherParam = null != $param4 ? ", {$param4}" : (null != $param5 ? ", {$param5}" : '');
        $select     = 'new \XoopsFormSelect(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$select}{$param1}, '{$param2}', \${$param3}{$otherParam});\n";
        } else {
            $ret = "{$select}{$param1}, '{$param2}', \${$param3}{$otherParam})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormTag
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param        $param4
     * @param int $param5
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormTag($var, $param1, $param2, $param3, $param4, int $param5 = 0, bool $isParam = false, string $t = "\t\t")
    {
        $tag = 'new \XoopsFormTag(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5});\n";
        } else {
            $ret = "{$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormButton
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param        $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormButton($var, $param1, $param2, $param3, $param4, bool $isParam = false, string $t = "\t\t")
    {
        $button = 'new \XoopsFormButton(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$button}'{$param1}', '{$param2}', {$param3}, '{$param4}');\n";
        } else {
            $ret = "{$button}'{$param1}', '{$param2}', {$param3}, '{$param4}')";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormPassword
     *
     * @param string $var
     * @param string $param1
     * @param string $param2
     * @param string $param3
     * @param string $param4
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormPassword(string $var, string $param1, string $param2, string $param3, string $param4, bool $isParam = false, string $t = "\t\t")
    {
        $tpassword      = 'new \XoopsFormPassword(';
        $getVarPassword = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param3, true);
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$tpassword}{$param1}, '{$param2}', '', {$getVarPassword});\n";
        } else {
            $ret = "{$tpassword}{$param1}, '{$param2}', {$param3}, {$param4})";
        }

        return $ret;
    }

    /**
     * @public function getClassXoopsFormSelectCountry
     *
     * @param        $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param null   $param4
     * @param null   $param5
     * @param bool $isParam
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsFormSelectCountry($var, $param1, $param2, $param3, $param4 = null, $param5 = null, bool $isParam = false, string $t = "\t\t")
    {
        $otherParam = null != $param4 ? ", {$param4}" : (null != $param5 ? ", {$param5}" : '');
        $select     = 'new \XoopsFormSelectCountry(';
        if (false === $isParam) {
            $ret = "{$t}\${$var} = {$select}{$param1}, '{$param2}', \${$param3}{$otherParam});\n";
        } else {
            $ret = "{$select}{$param1}, '{$param2}', \${$param3}{$otherParam})";
        }

        return $ret;
    }

    /**
     * @public   function getClassXoopsObjectTree
     *
     * @param string $var
     * @param        $param1
     * @param        $param2
     * @param        $param3
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsObjectTree(string $var, $param1, $param2, $param3, string $t = '')
    {
        return "{$t}\${$var} = new \XoopsObjectTree(\${$param1}, '{$param2}', '{$param3}');\n";
    }

    /**
     * @public function getClassXoopsMakeSelBox
     *
     * @param        $var
     * @param        $anchor
     * @param        $param1
     * @param        $param2
     * @param string $param3
     * @param        $param4
     * @param string $t
     *
     * @return string
     */
    public function getClassXoopsMakeSelBox($var, $anchor, $param1, $param2, string $param3, $param4, string $t = '')
    {
        $getVar = Modulebuilder\Files\CreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param4, true);
        return "{$t}\${$var} = \${$anchor}->makeSelBox('{$param1}', '{$param2}', '{$param3}', {$getVar}, true );\n";
    }

    /**
     * @public function getClassAddOption
     *
     * @param        $var
     * @param        $params
     * @param string $t
     *
     * @return string
     */
    public function getClassAddOption($var, $params, string $t = "\t\t")
    {
        return "{$t}\${$var}->addOption({$params});\n";
    }

    /**
     * @public function getClassAddOptionArray
     *
     * @param        $var
     * @param        $params
     * @param string $t
     *
     * @return string
     */
    public function getClassAddOptionArray($var, $params, string $t = "\t\t")
    {
        return "{$t}\${$var}->addOptionArray({$params});\n";
    }

    /**
     * @public function getClassAddElement
     *
     * @param string $var
     * @param string $params
     * @param string $t
     *
     * @return string
     */
    public function getClassAddElement(string $var = '', string $params = '', string $t = "\t\t")
    {
        return "{$t}\${$var}->addElement({$params});\n";
    }

    /**
     * @public function getClassSetDescription
     *
     * @param        $var
     * @param        $params
     * @param string $t
     *
     * @return string
     */
    public function getClassSetDescription($var, $params, string $t = "\t\t")
    {
        return "{$t}\${$var}->setDescription({$params});\n";
    }

    /**
     * @public function getClassSetExtra
     *
     * @param        $var
     * @param        $params
     * @param string $t
     *
     * @return string
     */
    public function getClassSetExtra($var, $params, string $t = "\t\t")
    {
        return "{$t}\${$var}->setExtra({$params});\n";
    }
}

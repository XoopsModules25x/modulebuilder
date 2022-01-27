<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files;

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * Class CreateSmartyCode.
 */
class CreateSmartyCode
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
    }

    /**
     * @static function getInstance
     * @param null
     * @return Files\CreateSmartyCode
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
     * @public function getSmartyTag
     *
     * @param string $tag
     * @param array  $attributes
     * @param string $content
     *
     * @param string $t
     * @return string
     */
    public function getSmartyTag($tag = '', $attributes = [], $content = '', $t = '')
    {
        if (empty($attributes)) {
            $attributes = [];
        }
        $attr = $this->getAttributes($attributes);
        $ret  = "{$t}<{{$tag}{$attr}}>{$content}<{/{$tag}}>";

        return $ret;
    }

    /**
     * @private function setAttributes
     * @param array $attributes
     *
     * @return string
     */
    private function getAttributes($attributes)
    {
        $str = '';
        foreach ($attributes as $name => $value) {
            if ('_' !== $name) {
                $str .= ' ' . $name . '="' . $value . '"';
            }
        }

        return $str;
    }

    /**
     * @public function getSmartyEmpty
     * @param string $empty
     *
     * @return string
     */
    public function getSmartyEmpty($empty = '')
    {
        return (string)$empty;
    }

    /**
     * @public function getSmartyComment
     * @param string $comment
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyComment($comment = '', $t = '', $n = "\n")
    {
        return "{$t}<{* {$comment} *}>{$n}";
    }

    /**
     * @public function getSmartyNoSimbol
     * @param string $noSimbol
     * @param string $t
     * @return string
     */
    public function getSmartyNoSimbol($noSimbol = '', $t = '')
    {
        return "{$t}<{{$noSimbol}}>";
    }

    /**
     * @public function getSmartyConst
     * @param string $language
     * @param mixed  $const
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyConst($language, $const, $t = '', $n = '')
    {
        return "{$t}<{\$smarty.const.{$language}{$const}}>{$n}";
    }

    /**
     * @public function getSmartySingleVar
     * @param string $var
     * @param string $t
     * @param string $n
     * @param string $default
     * @return string
     */
    public function getSmartySingleVar($var, $t = '', $n = '', $default = 'false')
    {
        $ret = "{$t}<{\${$var}";
        if ('' !== $default) {
            $ret .= '|default:' . $default;
        }
        $ret .= "}>{$n}";

        return $ret;
    }

    /**
     * @public function getSmartyDoubleVar
     * @param string $leftVar
     * @param string $rightVar
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyDoubleVar($leftVar, $rightVar, $t = '', $n = '')
    {
        return "{$t}<{\${$leftVar}.{$rightVar}}>{$n}";
    }

    /**
     * @public function getSmartyIncludeFile
     * @param        $moduleDirname
     * @param string $fileName
     * @param bool   $admin
     *
     * @param bool   $q
     * @param string $t
     * @param string $n
     * @param string $attributes
     * @return string
     */
    public function getSmartyIncludeFile($moduleDirname, $fileName = 'header', $admin = false, $q = false, $t = '', $n = "\n", $attributes = '')
    {
        $ret = '';
        if (!$admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_{$fileName}.tpl' {$attributes}}>{$n}";
        } elseif ($admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_admin_{$fileName}.tpl' {$attributes}}>{$n}";
        } elseif (!$admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_{$fileName}.tpl' {$attributes}}>{$n}";
        } elseif ($admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_admin_{$fileName}.tpl' {$attributes}}>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getSmartyIncludeFileListSection
     * @param        $moduleDirname
     * @param        $fileName
     * @param        $itemName
     * @param        $arrayName
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyIncludeFileListSection($moduleDirname, $fileName, $itemName, $arrayName, $t = '', $n = '')
    {
        return "{$t}<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$itemName}=\${$arrayName}[i]}>{$n}";
    }

    /**
     * @public function getSmartyIncludeFileListForeach
     * @param        $moduleDirname
     * @param        $fileName
     * @param        $tableFieldName
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyIncludeFileListForeach($moduleDirname, $fileName, $tableFieldName, $t = '', $n = '')
    {
        return "{$t}<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}}>{$n}";
    }

    /**
     * @public function getSmartyConditions
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     * @param bool   $count
     * @param bool   $noSimbol
     * @param string $t
     * @param string $n
     * @param bool   $split
     * @param mixed  $default
     * @return string
     */
    public function getSmartyConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $count = false, $noSimbol = false, $t = '', $n = "\n", $split = true, $default = 'string')
    {
        $ns = '';
        $ts = '';
        if ($split) {
            $ns = $n;
            $ts = $t;
        }
        if (!$count) {
            $ret = "{$t}<{if \${$condition}";
            if ('string' === $default) {
                $ret .= "|default:''";
            } elseif ('int' === $default) {
                $ret .= '|default:0';
            }
            $ret .= "{$operator}{$type}}>{$ns}";
        } elseif (!$noSimbol) {
            $ret = "{$t}<{if {$condition}";
            if ('string' === $default) {
                $ret .= "|default:''";
            } elseif ('int' === $default) {
                $ret .= '|default:0';
            }
            $ret .= "{$operator}{$type}}>{$ns}";
        } else {
            $ret = "{$t}<{if count(\${$condition}){$operator}{$type}}>{$ns}";
        }
        $ret .= "{$contentIf}";
        if ($contentElse) {
            $ret .= "{$ts}<{else}>{$ns}";
            $ret .= "{$contentElse}";
        }
        $ret .= "{$ts}<{/if}>{$n}";

        return $ret;
    }

    /**
     * @public function getSmartyForeach
     * @param string $item
     * @param string $from
     * @param string $content
     * @param string $name
     * @param string $key
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyForeach($item = 'item', $from = 'from', $content = 'content', $name = '', $key = '', $t = '', $n = "\n")
    {
        $name = '' != $name ? " name={$name}" : '';
        $key  = '' != $key ? " key={$key}" : '';
        $ret  = "{$t}<{foreach item={$item} from=\${$from}{$key}{$name}}>{$n}";
        $ret  .= "{$content}";
        $ret  .= "{$t}<{/foreach}>{$n}";

        return $ret;
    }

    /**
     * @public function getSmartyForeachQuery
     * @param string $item
     * @param string $from
     * @param string $content
     *
     * @param string $loop
     * @param string $key
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyForeachQuery($item = 'item', $from = 'from', $content = 'content', $loop = 'loop', $key = '', $t = '', $n = "\n")
    {
        $loop = '' != $loop ? " loop={$loop}" : '';
        $key  = '' != $key ? " key={$key}" : '';
        $ret  = "{$t}<{foreachq item={$item} from=\${$from}{$key}{$loop}}>{$n}";
        $ret  .= "{$content}";
        $ret  .= "{$t}<{/foreachq}>{$n}";

        return $ret;
    }

    /**
     * @public function getSmartySection
     * @param string $name
     * @param string $loop
     * @param string $content
     * @param int    $start
     * @param int    $step
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartySection($name = 'name', $loop = 'loop', $content = 'content', $start = 0, $step = 0, $t = '', $n = "\n")
    {
        $start = 0 != $start ? " start={$start}" : '';
        $step  = 0 != $step ? " step={$step}" : '';
        $ret   = "{$t}<{section name={$name} loop=\${$loop}{$start}{$step}}>{$n}";
        $ret   .= "{$content}";
        $ret   .= "{$t}<{/section}>{$n}";

        return $ret;
    }
}

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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class CreateHtmlSmartyCodes.
 */
class CreateHtmlSmartyCodes
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
     * @return Modulebuilder\Files\CreateHtmlSmartyCodes
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
     * @public function getHtmlTag
     * @param string $tag
     * @param array $attributes
     * @param string $content
     * @param bool $noClosed
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlTag($tag = '', $attributes = [], $content = '', $noClosed = false, $t = '', $n = "\n")
    {
        if (empty($attributes)) {
            $attributes = [];
        }
        $attr = $this->getAttributes($attributes);
        if ('br' === $tag) {
            $ret = "{$t}<{$tag}{$attr}>{$n}";
        } elseif ($noClosed) {
            $ret = "{$t}<{$tag}{$attr} />{$n}";
        } else {
            $ret = "{$t}<{$tag}{$attr}>{$content}</{$tag}>{$n}";
        }

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
     * @public function getHtmlEmpty
     * @param string $empty
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlEmpty($empty = '', $t = '', $n = "")
    {
        return "{$t}{$empty}{$n}";
    }

    /**
     * @public function getHtmlComment
     * @param string $htmlComment
     *
     * @param string $n
     * @return string
     */
    public function getHtmlComment($htmlComment = '', $n = '')
    {
        return "<!-- {$htmlComment} -->{$n}";
    }

    /**
     * @public function getHtmlBr
     * @param int $brNumb
     * @param string $htmlClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlBr($brNumb = 1, $htmlClass = '', $t = '', $n = "\n")
    {
        $brClass = ('' != $htmlClass) ? " class='{$htmlClass}'" : '';
        $ret     = '';
        for ($i = 0; $i < $brNumb; ++$i) {
            $ret .= "{$t}<br{$brClass} />{$n}";
        }

        return $ret;
    }

    /**
     * @public function getHtmlHNumb
     * @param string $content
     *
     * @param string $l
     * @param string $htmlHClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlHNumb($content = '', $l = '1', $htmlHClass = '', $t = '', $n = "\n")
    {
        $hClass = ('' != $htmlHClass) ? " class='{$htmlHClass}'" : '';
        $ret    = "{$t}<h{$l}{$hClass}>{$content}</h{$l}>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlDiv
     * @param string $content
     *
     * @param string $divClass
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlDiv($content = '', $divClass = '', $t = '', $n = "\n", $split = true)
    {
        $rDivClass = ('' != $divClass) ? " class='{$divClass}'" : '';

        if ($split) {
            $ret       = "{$t}<div{$rDivClass}>{$n}";
            $ret       .= "{$content}";
            $ret       .= "{$t}</div>{$n}";
        } else {
            $ret       = "{$t}<div{$rDivClass}>{$content}</div>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getHtmlPre
     * @param string $content
     *
     * @param string $preClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlPre($content = '', $preClass = '', $t = '', $n = "\n")
    {
        $rPreClass = ('' != $preClass) ? " class='{$preClass}'" : '';
        $ret       = "{$t}<pre{$rPreClass}>{$n}";
        $ret       .= "{$content}";
        $ret       .= "{$t}</pre>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlSpan
     * @param string $content
     *
     * @param string $spanClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlSpan($content = '', $spanClass = '', $t = '', $n = "\n")
    {
        $rSpanClass = ('' != $spanClass) ? " class='{$spanClass}'" : '';
        $ret        = "{$t}<span{$rSpanClass}>{$content}</span>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlParagraph
     * @param string $content
     *
     * @param string $pClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlParagraph($content = '', $pClass = '', $t = '', $n = "\n")
    {
        $rPClass = ('' != $pClass) ? " class='{$pClass}'" : '';
        $ret     = "{$t}<p{$rPClass}>{$n}";
        $ret     .= "{$content}";
        $ret     .= "{$t}</p>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlI
     * @param string $content
     *
     * @param string $iClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlI($content = '', $iClass = '', $t = '', $n = "\n")
    {
        $rIClass = ('' != $iClass) ? " class='{$iClass}'" : '';
        $ret     = "{$t}<i{$rIClass}>{$content}</i>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlUl
     * @param string $content
     *
     * @param string $ulClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlUl($content = '', $ulClass = '', $t = '', $n = "\n")
    {
        $rUlClass = ('' != $ulClass) ? " class='{$ulClass}'" : '';
        $ret      = "{$t}<ul{$rUlClass}>{$n}";
        $ret      .= "{$content}";
        $ret      .= "{$t}</ul>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlOl
     * @param string $content
     *
     * @param string $olClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlOl($content = '', $olClass = '', $t = '', $n = "\n")
    {
        $rOlClass = ('' != $olClass) ? " class='{$olClass}'" : '';
        $ret      = "{$t}<ol{$rOlClass}>{$n}";
        $ret      .= "{$content}";
        $ret      .= "{$t}</ol>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlLi
     * @param string $content
     * @param string $liClass
     *
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlLi($content = '', $liClass = '', $t = '', $n = "\n",  $split = false)
    {
        $rLiClass = ('' != $liClass) ? " class='{$liClass}'" : '';
        if ($split) {
            $ret       = "{$t}<li{$rLiClass}>{$n}";
            $ret       .= "{$content}";
            $ret       .= "{$t}</li>{$n}";
        } else {
            $ret       = "{$t}<li{$rLiClass}>{$content}</li>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getHtmlStrong
     * @param string $content
     * @param string $strongClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlStrong($content = '', $strongClass = '', $t = '', $n = '')
    {
        $rStrongClass = ('' != $strongClass) ? " class='{$strongClass}'" : '';

        return "{$t}<strong{$rStrongClass}>{$content}</strong>{$n}";
    }

    /**
     * @public function getHtmlAnchor
     * @param string $url
     * @param string $content
     * @param string $title
     * @param string $target
     * @param string $aClass
     *
     * @param string $rel
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlAnchor($url = '#', $content = '&nbsp;', $title = '', $target = '', $aClass = '', $rel = '', $t = '', $n = '')
    {
        $target  = ('' != $target) ? " target='{$target}'" : '';
        $rAClass = ('' != $aClass) ? " class='{$aClass}'" : '';
        $rel     = ('' != $rel) ? " rel='{$rel}'" : '';

        return "{$t}<a{$rAClass} href='{$url}' title='{$title}'{$target}{$rel}>{$content}</a>{$n}";
    }

    /**
     * @public function getHtmlImage
     * @param string $src
     * @param string $alt
     * @param string $imgClass
     *
     * @param string $t
     * @return string
     */
    public function getHtmlImage($src = 'blank.gif', $alt = 'blank.gif', $imgClass = '', $t = '')
    {
        $rImgClass = ('' != $imgClass) ? " class='{$imgClass}'" : '';
        $ret       = "{$t}<img{$rImgClass} src='{$src}' alt='{$alt}' />";

        return $ret;
    }

    /**
     * @public function getHtmlTable
     * @param string $content
     * @param string $tableClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlTable($content = '', $tableClass = '', $t = '', $n = "\n")
    {
        $rTableClass = ('' != $tableClass) ? " class='{$tableClass}'" : '';
        $ret         = "{$t}<table{$rTableClass}>{$n}";
        $ret         .= "{$content}";
        $ret         .= "{$t}</table>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlTableThead
     * @param string $content
     * @param string $theadClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlTableThead($content = '', $theadClass = '', $t = '', $n = "\n")
    {
        $rTheadClass = ('' != $theadClass) ? " class='{$theadClass}'" : '';
        $ret         = "{$t}<thead{$rTheadClass}>{$n}";
        $ret         .= "{$content}";
        $ret         .= "{$t}</thead>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlTableTbody
     * @param string $content
     * @param string $tbodyClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlTableTbody($content = '', $tbodyClass = '', $t = '', $n = "\n")
    {
        $rTbodyClass = ('' != $tbodyClass) ? " class='{$tbodyClass}'" : '';
        $ret         = "{$t}<tbody{$rTbodyClass}>{$n}";
        $ret         .= "{$content}";
        $ret         .= "{$t}</tbody>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlTableTfoot
     * @param string $content
     * @param string $tfootClass
     *
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlTableTfoot($content = '', $tfootClass = '', $t = '', $n = "\n", $split = true)
    {
        $rTfootClass = ('' != $tfootClass) ? " class='{$tfootClass}'" : '';
        if ($split) {
            $ret         = "{$t}<tfoot{$rTfootClass}>{$n}";
            $ret         .= "{$content}";
            $ret         .= "{$t}</tfoot>{$n}";
        } else {
            $ret         = "{$t}<tfoot{$rTfootClass}>{$content}</tfoot>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getHtmlTableRow
     * @param string $content
     * @param string $trClass
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlTableRow($content = '', $trClass = '', $t = '', $n = "\n")
    {
        $rTrClass = ('' != $trClass) ? " class='{$trClass}'" : '';
        $ret      = "{$t}<tr{$rTrClass}>{$n}";
        $ret      .= "{$content}";
        $ret      .= "{$t}</tr>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlTableHead
     * @param string $content
     * @param string $thClass
     * @param string $colspan
     *
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlTableHead($content = '', $thClass = '', $colspan = '', $t = '', $n = "\n", $split = false)
    {
        $rThClass = ('' != $thClass) ? " class='{$thClass}'" : '';
        $colspan  = ('' != $colspan) ? " colspan='{$colspan}'" : '';
        if ($split) {
            $ret      = "{$t}<th{$colspan}{$rThClass}>{$n}";
            $ret      .= "{$content}";
            $ret      .= "{$t}</th>{$n}";
        } else {
            $ret = "{$t}<th{$colspan}{$rThClass}>{$content}</th>{$n}";
        }
        return $ret;
    }

    /**
     * @public function getHtmlTableData
     * @param string $content
     * @param string $tdClass
     * @param string $colspan
     *
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlTableData($content = '', $tdClass = '', $colspan = '', $t = '', $n = "\n", $split = false)
    {
        $rTdClass = ('' != $tdClass) ? " class='{$tdClass}'" : '';
        $colspan  = ('' != $colspan) ? " colspan='{$colspan}'" : '';
        if ($split) {
            $ret      = "{$t}<td{$colspan}{$rTdClass}>{$n}";
            $ret      .= "{$content}";
            $ret      .= "{$t}</td>{$n}";
        } else {
            $ret = "{$t}<td{$colspan}{$rTdClass}>{$content}</td>{$n}";
        }
        return $ret;
    }

    /**
     * @public function getSmartyComment
     * @param string $comment
     *
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
     *
     * @return string
     */
    public function getSmartyNoSimbol($noSimbol = '')
    {
        return "<{{$noSimbol}}>";
    }

    /**
     * @public function getSmartyConst
     * @param string $language
     * @param mixed  $const
     *
     * @return string
     */
    public function getSmartyConst($language, $const)
    {
        return "<{\$smarty.const.{$language}{$const}}>";
    }

    /**
     * @public function getSmartySingleVar
     * @param string $var
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartySingleVar($var, $t = '', $n = "")
    {
        return "{$t}<{\${$var}}>{$n}";
    }

    /**
     * @public function getSmartyDoubleVar
     * @param string $leftVar
     * @param string $rightVar
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyDoubleVar($leftVar, $rightVar, $t = '', $n = "")
    {
        return "{$t}<{\${$leftVar}.{$rightVar}}>{$n}";
    }

    /**
     * @public function getSmartyIncludeFile
     * @param        $moduleDirname
     * @param string $fileName
     * @param bool $admin
     *
     * @param bool $q
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyIncludeFile($moduleDirname, $fileName = 'header', $admin = false, $q = false, $t = '', $n = "\n")
    {
        $ret = '';
        if (!$admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_{$fileName}.tpl'}>{$n}";
        } elseif ($admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>{$n}";
        } elseif (!$admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_{$fileName}.tpl'}>{$n}";
        } elseif ($admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getSmartyIncludeFileListSection
     * @param $moduleDirname
     * @param $fileName
     * @param $tableFieldName
     *
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyIncludeFileListSection($moduleDirname, $fileName, $tableFieldName, $t = '', $n = '')
    {
        return "{$t}<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}[i]}>{$n}";
    }

    /**
     * @public function getSmartyIncludeFileListForeach
     * @param $moduleDirname
     * @param $fileName
     * @param $tableFieldName
     *
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
     * @param mixed $contentElse
     * @param bool $count
     *
     * @param bool $noSimbol
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getSmartyConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $count = false, $noSimbol = false, $t = '', $n = "\n")
    {
        if (!$contentElse) {
            if (!$count) {
                $ret = "{$t}<{if \${$condition}{$operator}{$type}}>{$n}";
            } elseif (!$noSimbol) {
                $ret = "{$t}<{if {$condition}{$operator}{$type}}>{$n}";
            } else {
                $ret = "{$t}<{if count(\${$condition}){$operator}{$type}}>{$n}";
            }
            $ret .= "{$contentIf}";
            $ret .= "{$t}<{/if}>{$n}";
        } else {
            if (!$count) {
                $ret = "{$t}<{if \${$condition}{$operator}{$type}}>{$n}";
            } elseif (!$noSimbol) {
                $ret = "{$t}<{if {$condition}{$operator}{$type}}>{$n}";
            } else {
                $ret = "{$t}<{if count(\${$condition}){$operator}{$type}}>{$n}";
            }
            $ret .= "{$contentIf}";
            $ret .= "{$t}<{else}>{$n}";
            $ret .= "{$contentElse}";
            $ret .= "{$t}<{/if}>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getSmartyForeach
     * @param string $item
     * @param string $from
     * @param string $content
     *
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
     *
     * @param int $start
     * @param int $step
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

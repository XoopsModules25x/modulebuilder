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
 * Class CreateHtmlCode.
 */
class CreateHtmlCode
{
    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return CreateHtmlCode
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
     * @param string $t
     * @param string $n
     * @param bool $multiLine
     * @return string
     */
    public function getHtmlTag($tag = '', $attributes = [], $content = '', $noClosed = false, $t = '', $n = "\n", $multiLine = false)
    {
        if (empty($attributes)) {
            $attributes = [];
        }
        $attr = $this->getAttributes($attributes);
        if ('br' === $tag) {
            $ret = "{$t}<{$tag}{$attr}>{$n}";
        } elseif ($noClosed) {
            if ('img' === $tag) {
                $ret = "{$t}<{$tag}{$attr} >{$n}";
            } else {
                $ret = "{$t}<{$tag}{$attr} />{$n}";
            }
		} elseif ($multiLine) {
            $ret = "{$t}<{$tag}{$attr}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</{$tag}>{$n}";
        } else {
            $ret = "{$t}<{$tag}{$attr}>{$content}</{$tag}>{$n}";
        }

        return $ret;
    }

    /**
     * @private function setAttributes
     * @param array $attributes
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
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlEmpty($empty = '', $t = '', $n = '')
    {
        return "{$t}{$empty}{$n}";
    }

    /**
     * @public function getHtmlComment
     * @param string $htmlComment
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlComment($htmlComment = '', $t = '', $n = '')
    {
        return "{$t}<!-- {$htmlComment} -->{$n}";
    }

    /**
     * @public function getHtmlBr
     * @param int $brNumb
     * @param string $htmlClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlBr($brNumb = 1, $htmlClass = '', $t = '', $n = "\n")
    {
        $brClass = ('' != $htmlClass) ? " class='{$htmlClass}'" : '';
        $ret     = '';
        for ($i = 0; $i < $brNumb; ++$i) {
            $ret .= "{$t}<br{$brClass}>{$n}";
        }

        return $ret;
    }

    /**
     * @public function getHtmlHNumb
     * @param string $content
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
     * @param string $spanClass
     * @param string $t
     * @param string $n
     * @param bool $split
     * @return string
     */
    public function getHtmlSpan($content = '', $spanClass = '', $t = '', $n = "\n", $split = false)
    {
        $rSpanClass = ('' != $spanClass) ? " class='{$spanClass}'" : '';
        $ret        = "{$t}<span{$rSpanClass}>";
        if ($split) {
            $ret .= "\n";
        }
        $ret .= "{$content}";
        if ($split) {
            $ret .= "\n{$t}";
        }
        $ret .= "</span>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlParagraph
     * @param string $content
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
     * @param string $iClass
     * @param string $iId
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlI($content = '', $iClass = '', $iId = '', $t = '', $n = "\n")
    {
        $rIClass = ('' != $iClass) ? " class='{$iClass}'" : '';
        $rIId     = ('' != $iId) ? " id='{$iId}'" : '';
        $ret     = "{$t}<i{$rIClass}{$rIId}>{$content}</i>{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlUl
     * @param string $content
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
     * @param string $t
	 * @param string $n
     * @return string
     */
    public function getHtmlImage($src = 'blank.gif', $alt = 'blank.gif', $imgClass = '', $t = '', $n = '')
    {
        $rImgClass = ('' != $imgClass) ? " class='{$imgClass}'" : '';
        $ret       = "{$t}<img{$rImgClass} src='{$src}' alt='{$alt}' >{$n}";

        return $ret;
    }

    /**
     * @public function getHtmlTable
     * @param string $content
     * @param string $tableClass
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
}

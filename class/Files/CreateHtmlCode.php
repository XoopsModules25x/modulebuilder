<?php declare(strict_types=1);

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
 */

/**
 * Class CreateHtmlCode.
 */
class CreateHtmlCode
{
    /**
     * @static function getInstance
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
    public function getHtmlTag(string $tag = '', array $attributes = [], string $content = '', bool $noClosed = false, string $t = '', string $n = "\n", bool $multiLine = false)
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
    private function getAttributes(array $attributes)
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
    public function getHtmlEmpty(string $empty = '', string $t = '', string $n = '')
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
    public function getHtmlComment(string $htmlComment = '', string $t = '', string $n = '')
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
    public function getHtmlBr(int $brNumb = 1, string $htmlClass = '', string $t = '', string $n = "\n")
    {
        $brClass = ('' != $htmlClass) ? " class='{$htmlClass}'" : '';
        return str_repeat("{$t}<br{$brClass}>{$n}", $brNumb);
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
    public function getHtmlHNumb(string $content = '', string $l = '1', string $htmlHClass = '', string $t = '', string $n = "\n")
    {
        $hClass = ('' != $htmlHClass) ? " class='{$htmlHClass}'" : '';
        return "{$t}<h{$l}{$hClass}>{$content}</h{$l}>{$n}";
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
    public function getHtmlDiv(string $content = '', string $divClass = '', string $t = '', string $n = "\n", bool $split = true)
    {
        $rDivClass = ('' != $divClass) ? " class='{$divClass}'" : '';

        if ($split) {
            $ret = "{$t}<div{$rDivClass}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</div>{$n}";
        } else {
            $ret = "{$t}<div{$rDivClass}>{$content}</div>{$n}";
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
    public function getHtmlPre(string $content = '', string $preClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlSpan(string $content = '', string $spanClass = '', string $t = '', string $n = "\n", bool $split = false)
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
    public function getHtmlParagraph(string $content = '', string $pClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlI(string $content = '', string $iClass = '', string $iId = '', string $t = '', string $n = "\n")
    {
        $rIClass = ('' != $iClass) ? " class='{$iClass}'" : '';
        $rIId    = ('' != $iId) ? " id='{$iId}'" : '';
        return "{$t}<i{$rIClass}{$rIId}>{$content}</i>{$n}";
    }

    /**
     * @public function getHtmlUl
     * @param string $content
     * @param string $ulClass
     * @param string $t
     * @param string $n
     * @return string
     */
    public function getHtmlUl(string $content = '', string $ulClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlOl(string $content = '', string $olClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlLi(string $content = '', string $liClass = '', string $t = '', string $n = "\n", bool $split = false)
    {
        $rLiClass = ('' != $liClass) ? " class='{$liClass}'" : '';
        if ($split) {
            $ret = "{$t}<li{$rLiClass}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</li>{$n}";
        } else {
            $ret = "{$t}<li{$rLiClass}>{$content}</li>{$n}";
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
    public function getHtmlStrong(string $content = '', string $strongClass = '', string $t = '', string $n = '')
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
    public function getHtmlAnchor(string $url = '#', string $content = '&nbsp;', string $title = '', string $target = '', string $aClass = '', string $rel = '', string $t = '', string $n = '')
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
    public function getHtmlImage(string $src = 'blank.gif', string $alt = 'blank.gif', string $imgClass = '', string $t = '', string $n = '')
    {
        $rImgClass = ('' != $imgClass) ? " class='{$imgClass}'" : '';
        if (strpos($src,"'")) {
            // function getSmartyNoSimbol is used before
            $ret = "{$t}<img{$rImgClass} src=\"{$src}\" alt='{$alt}' >{$n}";
        } else {
            $ret = "{$t}<img{$rImgClass} src='{$src}' alt='{$alt}' >{$n}";
        }

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
    public function getHtmlTable(string $content = '', string $tableClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlTableThead(string $content = '', string $theadClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlTableTbody(string $content = '', string $tbodyClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlTableTfoot(string $content = '', string $tfootClass = '', string $t = '', string $n = "\n", bool $split = true)
    {
        $rTfootClass = ('' != $tfootClass) ? " class='{$tfootClass}'" : '';
        if ($split) {
            $ret = "{$t}<tfoot{$rTfootClass}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</tfoot>{$n}";
        } else {
            $ret = "{$t}<tfoot{$rTfootClass}>{$content}</tfoot>{$n}";
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
    public function getHtmlTableRow(string $content = '', string $trClass = '', string $t = '', string $n = "\n")
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
    public function getHtmlTableHead(string $content = '', string $thClass = '', string $colspan = '', string $t = '', string $n = "\n", bool $split = false)
    {
        $rThClass = ('' != $thClass) ? " class='{$thClass}'" : '';
        $colspan  = ('' != $colspan) ? " colspan='{$colspan}'" : '';
        if ($split) {
            $ret = "{$t}<th{$colspan}{$rThClass}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</th>{$n}";
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
    public function getHtmlTableData(string $content = '', string $tdClass = '', string $colspan = '', string $t = '', string $n = "\n", bool $split = false)
    {
        $rTdClass = ('' != $tdClass) ? " class='{$tdClass}'" : '';
        $colspan  = ('' != $colspan) ? " colspan='{$colspan}'" : '';
        if ($split) {
            $ret = "{$t}<td{$colspan}{$rTdClass}>{$n}";
            $ret .= "{$content}";
            $ret .= "{$t}</td>{$n}";
        } else {
            $ret = "{$t}<td{$colspan}{$rTdClass}>{$content}</td>{$n}";
        }

        return $ret;
    }
}

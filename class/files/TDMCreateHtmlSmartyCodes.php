<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: TDMCreateHtmlSmartyCodes.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TDMCreateHtmlSmartyCodes.
 */
class TDMCreateHtmlSmartyCodes
{
    /**
    *  @public function constructor
    *  @param null
    */
    public function __construct()
    {
    }

    /**
    *  @static function &getInstance
     * @return TDMCreateHtmlSmartyCodes
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
    *  @public function getHtmlTag
     * @param string $tag
     * @param array  $attributes
     * @param string $content
     * @param bool   $noClosed
     * @param string $t
     * @return string
     * @internal param $closed
     */
    public function getHtmlTag($tag = '', $attributes = array(), $content = '', $noClosed = false, $t = '')
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        $attr = $this->getAttributes($attributes);
        if ($noClosed) {
            $ret = "{$t}<{$tag}{$attr} />\n";
        } else {
            $ret = "{$t}<{$tag}{$attr}>{$content}</{$tag}>\n";
        }

        return $ret;
    }

     /**
    *  @private function setAttributes
     * @param  $attributes
     *
     * @return string
     */
    private function getAttributes($attributes)
    {
        $str = '';
        foreach ($attributes as $name => $value) {
            if ($name != '_') {
                $str .= ' '.$name.'="'.$value.'"';
            }
        }

        return $str;
    }

    /**
    *  @public function getHtmlEmpty
     * @param $empty
     *
     * @return string
     */
    public function getHtmlEmpty($empty = '')
    {
        return "{$empty}";
    }

    /**
    *  @public function getHtmlComment
     * @param $htmlComment
     *
     * @return string
     */
    public function getHtmlComment($htmlComment = '')
    {
        return "<!-- {$htmlComment} -->";
    }

    /**
    *  @public function getHtmlBr
     * @param int    $brNumb
     * @param string $htmlClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlBr($brNumb = 1, $htmlClass = '', $t = '')
    {
        $brClass = ($htmlClass != '') ? " class='{$htmlClass}'" : '';
        $ret = '';
        for ($i = 0; $i < $brNumb; ++$i) {
            $ret .= "{$t}<br{$brClass} />\n";
        }

        return $ret;
    }

    /**
    *  @public function getHtmlHNumb
     * @param string $content
     * @param string $n
     * @param string $htmlHClass
     *
     * @param string $t
     * @return string
     */
    public function getHtmlHNumb($content = '', $n = '1', $htmlHClass = '', $t = '')
    {
        $hClass = ($htmlHClass != '') ? " class='{$htmlHClass}'" : '';
        $ret = "{$t}<h{$n}{$hClass}>{$content}</h{$n}>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlDiv
     * @param string $content
     * @param string $divClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlDiv($content = '', $divClass = '', $t = '')
    {
        $rDivClass = ($divClass != '') ? " class='{$divClass}'" : '';
        $ret = "{$t}<div{$rDivClass}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}</div>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlPre
     * @param string $content
     * @param string $preClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlPre($content = '', $preClass = '', $t = '')
    {
        $rPreClass = ($preClass != '') ? " class='{$preClass}'" : '';
        $ret = "{$t}<pre{$rPreClass}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}</pre>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlSpan
     * @param string $content
     * @param string $spanClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlSpan($content = '', $spanClass = '', $t = '')
    {
        $rSpanClass = ($spanClass != '') ? " class='{$spanClass}'" : '';
        $ret = "{$t}<span{$rSpanClass}>{$content}</span>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlParagraph
     * @param string $content
     * @param string $pClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlParagraph($content = '', $pClass = '', $t = '')
    {
        $rPClass = ($pClass != '') ? " class='{$pClass}'" : '';
        $ret = "{$t}<p{$rPClass}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}</p>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlI
     * @param string $content
     * @param string $iClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlI($content = '', $iClass = '', $t = '')
    {
        $rIClass = ($iClass != '') ? " class='{$iClass}'" : '';
        $ret = "{$t}<i{$rIClass}>{$content}</i>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlUl
     * @param string $content
     * @param string $ulClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlUl($content = '', $ulClass = '', $t = '')
    {
        $rUlClass = ($ulClass != '') ? " class='{$ulClass}'" : '';
        $ret = "<ul{$rUlClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</ul>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlOl
     * @param string $content
     * @param string $olClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlOl($content = '', $olClass = '', $t = '')
    {
        $rOlClass = ($olClass != '') ? " class='{$olClass}'" : '';
        $ret = "<ol{$rOlClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</ol>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlLi
     * @param string $content
     * @param string $liClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlLi($content = '', $liClass = '', $t = '')
    {
        $rLiClass = ($liClass != '') ? " class='{$liClass}'" : '';

        return "<li{$rLiClass}>{$content}</li>";
    }

    /**
    *  @public function getHtmlStrong
     * @param string $content
     * @param string $strongClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlStrong($content = '', $strongClass = '', $t = '')
    {
        $rStrongClass = ($strongClass != '') ? " class='{$strongClass}'" : '';

        return "<strong{$rStrongClass}>{$content}</strong>";
    }

    /**
    *  @public function getHtmlAnchor
     * @param string $url
     * @param string $content
     * @param string $title
     * @param string $target
     * @param string $aClass
     * @param string $rel
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlAnchor($url = '#', $content = '&nbsp;', $title = '', $target = '', $aClass = '', $rel = '', $t = '')
    {
        $target = ($target != '') ? " target='{$target}'" : '';
        $rAClass = ($aClass != '') ? " class='{$aClass}'" : '';
        $rel = ($rel != '') ? " rel='{$rel}'" : '';

        return "<a{$rAClass} href='{$url}' title='{$title}'{$target}{$rel}>{$content}</a>";
    }

    /**
    *  @public function getHtmlImage
     * @param string $src
     * @param string $alt
     * @param string $imgClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlImage($src = 'blank.gif', $alt = 'blank.gif', $imgClass = '', $t = '')
    {
        $rImgClass = ($imgClass != '') ? " class='{$imgClass}'" : '';
        $ret = "<img{$rImgClass} src='{$src}' alt='{$alt}' />";

        return $ret;
    }

    /**
    *  @public function getHtmlTable
     * @param string $content
     * @param string $tableClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTable($content = '', $tableClass = '', $t = '')
    {
        $rTableClass = ($tableClass != '') ? " class='{$tableClass}'" : '';
        $ret = "<table{$rTableClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</table>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlTableThead
     * @param string $content
     * @param string $theadClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTableThead($content = '', $theadClass = '', $t = '')
    {
        $rTheadClass = ($theadClass != '') ? " class='{$theadClass}'" : '';
        $ret = "\t<thead{$rTheadClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</thead>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlTableTbody
     * @param string $content
     * @param string $tbodyClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTableTbody($content = '', $tbodyClass = '', $t = '')
    {
        $rTbodyClass = ($tbodyClass != '') ? " class='{$tbodyClass}'" : '';
        $ret = "\t<tbody{$rTbodyClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tbody>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlTableTfoot
     * @param string $content
     * @param string $tfootClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTableTfoot($content = '', $tfootClass = '', $t = '')
    {
        $rTfootClass = ($tfootClass != '') ? " class='{$tfootClass}'" : '';
        $ret = "\t<tfoot{$rTfootClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tfoot>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlTableRow
     * @param string $content
     * @param string $trClass
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTableRow($content = '', $trClass = '', $t = '')
    {
        $rTrClass = ($trClass != '') ? " class='{$trClass}'" : '';
        $ret = "\t<tr{$rTrClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tr>\n";

        return $ret;
    }

    /**
    *  @public function getHtmlTableHead
     * @param string $content
     * @param string $thClass
     * @param string $colspan
     *
     * @param string $t
     * @return string
     * @internal param $class
     */
    public function getHtmlTableHead($content = '', $thClass = '', $colspan = '', $t = '')
    {
        $rThClass = ($thClass != '') ? " class='{$thClass}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';

        return "<th{$colspan}{$rThClass}>{$content}</th>";
    }

    /**
    *  @public function getHtmlTableData
     * @param string $content
     * @param string $tdClass
     * @param string $colspan
     * @return string
     * @internal param $class
     */
    public function getHtmlTableData($content = '', $tdClass = '', $colspan = '')
    {
        $rTdClass = ($tdClass != '') ? " class='{$tdClass}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';

        return "<td{$colspan}{$rTdClass}>{$content}</td>";
    }

    /**
    *  @public function getSmartyComment
     * @param $comment
     *
     * @return string
     */
    public function getSmartyComment($comment = '')
    {
        return "<{* {$comment} *}>";
    }

    /**
    *  @public function getSmartyNoSimbol
     * @param string $noSimbol
     * @return string
     * @internal param $content
     *
     */
    public function getSmartyNoSimbol($noSimbol = '')
    {
        return "<{{$noSimbol}}>";
    }

    /**
    *  @public function getSmartyConst
     * @param $language
     * @param $const
     *
     * @return string
     */
    public function getSmartyConst($language, $const)
    {
        return "<{\$smarty.const.{$language}{$const}}>";
    }

    /**
    *  @public function getSmartySingleVar
     * @param string $var
     *
     * @return string
     */
    public function getSmartySingleVar($var)
    {
        return "<{\${$var}}>";
    }

    /**
    *  @public function getSmartyDoubleVar
     * @param string $leftVar
     * @param string $rightVar
     *
     * @return string
     */
    public function getSmartyDoubleVar($leftVar, $rightVar)
    {
        return "<{\${$leftVar}.{$rightVar}}>";
    }

    /**
    *  @public function getSmartyIncludeFile
     * @param        $moduleDirname
     * @param string $fileName
     * @param bool   $admin
     *
     * @param bool   $q
     * @return string
     */
    public function getSmartyIncludeFile($moduleDirname, $fileName = 'header', $admin = false, $q = false)
    {
        if (!$admin && !$q) {
            $ret = "<{include file='db:{$moduleDirname}_{$fileName}.tpl'}>\n";
        } elseif ($admin && !$q) {
            $ret = "<{include file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>\n";
        } elseif (!$admin && $q) {
            $ret = "<{includeq file='db:{$moduleDirname}_{$fileName}.tpl'}>\n";
        } elseif ($admin && $q) {
            $ret = "<{includeq file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>\n";
        }

        return $ret;
    }

    /**
    *  @public function getSmartyIncludeFileListSection
     * @param $moduleDirname
     * @param $fileName
     * @param $tableFieldName
     *
     * @return string
     */
    public function getSmartyIncludeFileListSection($moduleDirname, $fileName, $tableFieldName)
    {
        return "<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}[i]}>";
    }

    /**
    *  @public function getSmartyIncludeFileListForeach
      * @param $moduleDirname
     * @param $fileName
     * @param $tableFieldName
     *
     * @return string
     */
    public function getSmartyIncludeFileListForeach($moduleDirname, $fileName, $tableFieldName)
    {
        return "<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}}>";
    }

    /**
    *  @public function getSmartyConditions
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     * @param bool   $count
     *
     * @param bool   $noSimbol
     * @return string
     */
    public function getSmartyConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $count = false, $noSimbol = false)
    {
        if (!$contentElse) {
            if (!$count) {
                $ret = "<{if \${$condition}{$operator}{$type}}>\n";
            } elseif (!$noSimbol) {
                $ret = "<{if {$condition}{$operator}{$type}}>\n";
            } else {
                $ret = "<{if count(\${$condition}){$operator}{$type}}>\n";
            }
            $ret .= "\t{$contentIf}\n";
            $ret .= "<{/if}>\n";
        } else {
            if (!$count) {
                $ret = "<{if \${$condition}{$operator}{$type}}>\n";
            } elseif (!$noSimbol) {
                $ret = "<{if {$condition}{$operator}{$type}}>\n";
            } else {
                $ret = "<{if count(\${$condition}){$operator}{$type}}>\n";
            }
            $ret .= "\t{$contentIf}\n";
            $ret .= "<{else}>\n";
            $ret .= "\t{$contentElse}\n";
            $ret .= "<{/if}>\n";
        }

        return $ret;
    }

    /**
    *  @public function getSmartyForeach
     * @param string $item
     * @param string $from
     * @param string $content
     *
     * @param string $name
     * @param string $key
     * @return string
     */
    public function getSmartyForeach($item = 'item', $from = 'from', $content = 'content', $name = '', $key = '')
    {
        $name = $name != '' ? " name={$name}" : '';
        $key = $key != '' ? " key={$key}" : '';
        $ret = "<{foreach item={$item} from=\${$from}{$key}{$name}}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "<{/foreach}>\n";

        return $ret;
    }

    /**
    *  @public function getSmartyForeachQuery
     * @param string $item
     * @param string $from
     * @param string $content
     *
     * @param string $loop
     * @param string $key
     * @return string
     */
    public function getSmartyForeachQuery($item = 'item', $from = 'from', $content = 'content', $loop = 'loop', $key = '')
    {
        $loop = $loop != '' ? " loop={$loop}" : '';
        $key = $key != '' ? " key={$key}" : '';
        $ret = "<{foreachq item={$item} from=\${$from}{$key}{$loop}}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "<{/foreachq}>\n";

        return $ret;
    }

    /**
    *  @public function getSmartySection
     * @param string $name
     * @param string $loop
     * @param string $content
     *
     * @param int    $start
     * @param int    $step
     * @return string
     */
    public function getSmartySection($name = 'name', $loop = 'loop', $content = 'content', $start = 0, $step = 0)
    {
        $start = $start != 0 ? " start={$start}" : '';
        $step = $step != 0 ? " step={$step}" : '';
        $ret = "<{section name={$name} loop=\${$loop}{$start}{$step}}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "<{/section}>\n";

        return $ret;
    }
}

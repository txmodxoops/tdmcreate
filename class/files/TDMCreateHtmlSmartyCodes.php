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
    /*
    * @var string
    */
    protected $htmlcode;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct(){}

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
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

    /*
    *  @public function getHtmlTag
    *  @param string $tag
    *  @param array  $attributes
    *  @param string $content
    *  @param bool   $closed
    */
    /**
     * @param $tag
     * @param $attributes
     * @param $content
     * @param $closed
     *
     * @return string
     */
    public function getHtmlTag($tag = '', $attributes = array(), $content = '', $closed = true)
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        $attr = $this->getAttributes($attributes);
        if (!$closed) {
            $ret = "<{$tag}{$attr} />";
        } else {
            $ret = "<{$tag}{$attr}>{$content}</{$tag}>";
        }

        return $ret;
    }

     /*
    *  @private function setAttributes
    *  @param array $attributes
    */
    /**
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

    /*
    *  @public function getHtmlEmpty
    *  @param string $empty
    */
    /**
     * @param $empty
     *
     * @return string
     */
    public function getHtmlEmpty($empty = '')
    {
        return "{$empty}";
    }

    /*
    *  @public function getHtmlComment
    *  @param string $htmlComment
    */
    /**
     * @param $htmlComment
     *
     * @return string
     */
    public function getHtmlComment($htmlComment = '')
    {
        return "<!-- {$htmlComment} -->";
    }

    /*
    *  @public function getHtmlBr
    *  @param string $brNumb
    *  @param string $class
    */
    /**
     * @param $brNumb
     * @param $class
     *
     * @return string
     */
    public function getHtmlBr($brNumb = 1, $htmlClass = '')
    {
        $brClass = ($htmlClass != '') ? " class='{$htmlClass}'" : '';
        $ret = '';
        for ($i = 0; $i < $brNumb; ++$i) {
            $ret .= "<br{$brClass} />";
        }

        return $ret;
    }

    /*
    *  @public function getHtmlHNumb
    *  @param string $htmlHClass
    *  @param string $content
    */
    /**
     * @param $content
     * @param $htmlHClass
     *
     * @return string
     */
    public function getHtmlHNumb($content = '', $n = '1', $htmlHClass = '')
    {
        $hClass = ($htmlHClass != '') ? " class='{$htmlHClass}'" : '';
        $ret = "<h{$n}{$hClass}>{$content}</h{$n}>";

        return $ret;
    }

    /*
    *  @public function getHtmlDiv
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlDiv($content = '', $divClass = '')
    {
        $rDivClass = ($divClass != '') ? " class='{$divClass}'" : '';
        $ret = "<div{$rDivClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</div>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlPre
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlPre($content = '', $preClass = '')
    {
        $rPreClass = ($preClass != '') ? " class='{$preClass}'" : '';
        $ret = "<pre{$rPreClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</pre>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlSpan
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlSpan($content = '', $spanClass = '')
    {
        $rSpanClass = ($spanClass != '') ? " class='{$spanClass}'" : '';
        $ret = "<span{$rSpanClass}>{$content}</span>";

        return $ret;
    }

    /*
    *  @public function getHtmlParagraph
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlParagraph($content = '', $pClass = '')
    {
        $rPClass = ($pClass != '') ? " class='{$pClass}'" : '';
        $ret = "<p{$rPClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</p>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlI
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlI($content = '', $iClass = '')
    {
        $rIClass = ($iClass != '') ? " class='{$iClass}'" : '';
        $ret = "<i{$rIClass}>{$content}</i>";

        return $ret;
    }

    /*
    *  @public function getHtmlUl
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlUl($content = '', $ulClass = '')
    {
        $rUlClass = ($ulClass != '') ? " class='{$ulClass}'" : '';
        $ret = "<ul{$rUlClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</ul>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlOl
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlOl($content = '', $olClass = '')
    {
        $rOlClass = ($olClass != '') ? " class='{$olClass}'" : '';
        $ret = "<ol{$rOlClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</ol>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlLi
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlLi($content = '', $liClass = '')
    {
        $rLiClass = ($liClass != '') ? " class='{$liClass}'" : '';

        return "<li{$rLiClass}>{$content}</li>";
    }

    /*
    *  @public function getHtmlStrong
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlStrong($content = '', $strongClass = '')
    {
        $rStrongClass = ($strongClass != '') ? " class='{$strongClass}'" : '';

        return "<strong{$rStrongClass}>{$content}</strong>";
    }

    /*
    *  @public function getHtmlAnchor
    *  @param string $class
    *  @param string $url
    *  @param string $target
    *  @param string $content
    */
    /**
     * @param $url
     * @param $content
     * @param $target
     * @param $class
     *
     * @return string
     */
    public function getHtmlAnchor($url = '#', $content = '&nbsp;', $title = '', $target = '', $aClass = '', $rel = '')
    {
        $target = ($target != '') ? " target='{$target}'" : '';
        $rAClass = ($aClass != '') ? " class='{$aClass}'" : '';
        $rel = ($rel != '') ? " rel='{$rel}'" : '';

        return "<a{$rAClass} href='{$url}' title='{$title}'{$target}{$rel}>{$content}</a>";
    }

    /*
    *  @public function getHtmlImage
    *  @param string $src
    *  @param string $alt
    *  @param string $class
    */
    /**
     * @param $src
     * @param $alt
     * @param $class
     *
     * @return string
     */
    public function getHtmlImage($src = 'blank.gif', $alt = 'blank.gif', $imgClass = '')
    {
        $rImgClass = ($imgClass != '') ? " class='{$imgClass}'" : '';
        $ret = "<img{$rImgClass} src='{$src}' alt='{$alt}' />";

        return $ret;
    }

    /*
    *  @public function getHtmlTable
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlTable($content = '', $tableClass = '')
    {
        $rTableClass = ($tableClass != '') ? " class='{$tableClass}'" : '';
        $ret = "<table{$rTableClass}>\n";
        $ret .= "\t{$content}\n";
        $ret .= "</table>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlTableThead
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlTableThead($content = '', $theadClass = '')
    {
        $rTheadClass = ($theadClass != '') ? " class='{$theadClass}'" : '';
        $ret = "\t<thead{$rTheadClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</thead>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlTableTbody
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlTableTbody($content = '', $tbodyClass = '')
    {
        $rTbodyClass = ($tbodyClass != '') ? " class='{$tbodyClass}'" : '';
        $ret = "\t<tbody{$rTbodyClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tbody>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlTableTfoot
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlTableTfoot($content = '', $tfootClass = '')
    {
        $rTfootClass = ($tfootClass != '') ? " class='{$tfootClass}'" : '';
        $ret = "\t<tfoot{$rTfootClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tfoot>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlTableRow
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlTableRow($content = '', $trClass = '')
    {
        $rTrClass = ($trClass != '') ? " class='{$trClass}'" : '';
        $ret = "\t<tr{$rTrClass}>\n";
        $ret .= "\t\t{$content}\n";
        $ret .= "\t</tr>\n";

        return $ret;
    }

    /*
    *  @public function getHtmlTableHead
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     * @param $colspan
     *
     * @return string
     */
    public function getHtmlTableHead($content = '', $thClass = '', $colspan = '')
    {
        $rThClass = ($thClass != '') ? " class='{$thClass}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';

        return "<th{$colspan}{$rThClass}>{$content}</th>";
    }

    /*
    *  @public function getHtmlTableData
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     * @param $colspan
     *
     * @return string
     */
    public function getHtmlTableData($content = '', $tdClass = '', $colspan = '')
    {
        $rTdClass = ($tdClass != '') ? " class='{$tdClass}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';

        return "<td{$colspan}{$rTdClass}>{$content}</td>";
    }

    /*
    *  @public function getSmartyComment
    *  @param string $comment
    */
    /**
     * @param $comment
     *
     * @return string
     */
    public function getSmartyComment($comment = '')
    {
        return "<{* {$comment} *}>";
    }

    /*
    *  @public function getSmartyNoSimbol
    *  @param string $content
    */
    /**
     * @param $content
     *
     * @return string
     */
    public function getSmartyNoSimbol($noSimbol = '')
    {
        return "<{{$noSimbol}}>";
    }

    /*
    *  @public function getSmartyConst
    *  @param string $language
    *  @param mixed $const
    */
    /**
     * @param $language
     * @param $const
     *
     * @return string
     */
    public function getSmartyConst($language, $const)
    {
        return "<{\$smarty.const.{$language}{$const}}>";
    }

    /*
    *  @public function getSmartySingleVar
    *  @param string $var
    */
    /**
     * @param string $var
     *
     * @return string
     */
    public function getSmartySingleVar($var)
    {
        return "<{\${$var}}>";
    }

    /*
    *  @public function getSmartyDoubleVar
    *  @param string $leftVar
    *  @param string $rightVar
    */
    /**
     * @param string $leftVar
     * @param string $rightVar
     *
     * @return string
     */
    public function getSmartyDoubleVar($leftVar, $rightVar)
    {
        return "<{\${$leftVar}.{$rightVar}}>";
    }

    /*
    *  @public function getSmartyIncludeFile
    *  @param string $name
    */
    /**
     * @param $moduleDirname
     * @param $fileName
     * @param $admin
     *
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

    /*
    *  @public function getSmartyIncludeFileListSection
    *  @param string $name
    */
    /**
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

    /*
    *  @public function getSmartyIncludeFileListForeach
    *  @param string $name
    */
    /**
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

    /*
    *  @public function getSmartyConditions
    *  @param string $condition
    *  @param string $operator
    *  @param string $type
    *  @param string $contentIf
    *  @param mixed  $contentElse
    *  @param bool   $count
    */
    /**
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     * @param bool   $count
     *
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

    /*
    *  @public function getSmartyForeach
    *  @param string $item
    *  @param string $from
    *  @param string $content
    */
    /**
     * @param string $item
     * @param string $from
     * @param string $content
     *
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

    /*
    *  @public function getSmartyForeachQuery
    *  @param string $item
    *  @param string $from
    *  @param string $content
    */
    /**
     * @param string $item
     * @param string $from
     * @param string $content
     *
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

    /*
    *  @public function getSmartySection
    *  @param string $name
    *  @param string $loop
    *  @param string $content
    */
    /**
     * @param string $name
     * @param string $loop
     * @param string $content
     *
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

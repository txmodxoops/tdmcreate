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
 * @version         $Id: TDMCreateSmartyCode.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TDMCreateSmartyCode.
 */
class TDMCreateSmartyCode
{
    /*
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return TDMCreateSmartyCode
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
     * @param $tag
     * @param $attributes
     * @param $content
     *
     * @return string
     */
    public function getSmartyTag($tag = '', $attributes = array(), $content = '', $t = '')
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        $attr = $this->getAttributes($attributes);
        $ret = "{$t}<{{$tag}{$attr}}>{$content}<{/{$tag}}>";

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
            if ($name !== '_') {
                $str .= ' '.$name.'="'.$value.'"';
            }
        }

        return $str;
    }

    /*
    *  @public function getSmartyEmpty
    *  @param string $empty
    */
    /**
     * @param $empty
     *
     * @return string
     */
    public function getSmartyEmpty($empty = '')
    {
        return "{$empty}";
    }

    /*
    *  @public function getSmartyComment
    *  @param string $htmlComment
    */
    /**
     * @param $htmlComment
     *
     * @return string
     */
    public function getSmartyComment($smartyComment = '', $t = '')
    {
        return "{$t}<{* {$smartyComment} *}>";
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
    public function getSmartyNoSimbol($noSimbol = '', $t = '')
    {
        return "{$t}<{{$noSimbol}}>";
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
    public function getSmartyConst($language, $const, $t = '')
    {
        return "{$t}<{\$smarty.const.{$language}{$const}}>";
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
    public function getSmartySingleVar($var, $t = '')
    {
        return "{$t}<{\${$var}}>";
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
    public function getSmartyDoubleVar($leftVar, $rightVar, $t = '')
    {
        return "{$t}<{\${$leftVar}.{$rightVar}}>";
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
    public function getSmartyIncludeFile($moduleDirname, $fileName = 'header', $admin = false, $q = false, $t = '')
    {
        if (!$admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_{$fileName}.tpl'}>\n";
        } elseif ($admin && !$q) {
            $ret = "{$t}<{include file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>\n";
        } elseif (!$admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_{$fileName}.tpl'}>\n";
        } elseif ($admin && $q) {
            $ret = "{$t}<{includeq file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>\n";
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
    public function getSmartyIncludeFileListSection($moduleDirname, $fileName, $tableFieldName, $t = '')
    {
        return "{$t}<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}[i]}>\n";
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
    public function getSmartyIncludeFileListForeach($moduleDirname, $fileName, $tableFieldName, $t = '')
    {
        return "{$t}<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}}>\n";
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
    public function getSmartyConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $count = false, $noSimbol = false, $t = '')
    {
        $ret = '';
        if (!$contentElse) {
            if (!$count) {
                $ret = "{$t}<{if \${$condition}{$operator}{$type}}>\n";
            } elseif (!$noSimbol) {
                $ret = "{$t}<{if {$condition}{$operator}{$type}}>\n";
            } else {
                $ret = "{$t}<{if count(\${$condition}){$operator}{$type}}>\n";
            }
            $ret .= "{$t}{$contentIf}";
            $ret .= "{$t}<{/if}>\n";
        } else {
            if (!$count) {
                $ret = "{$t}<{if \${$condition}{$operator}{$type}}>\n";
            } elseif (!$noSimbol) {
                $ret = "{$t}<{if {$condition}{$operator}{$type}}>\n";
            } else {
                $ret = "{$t}<{if count(\${$condition}){$operator}{$type}}>\n";
            }
            $ret .= "{$t}{$contentIf}";
            $ret .= "{$t}<{else}>\n";
            $ret .= "{$t}{$contentElse}";
            $ret .= "{$t}<{/if}>\n";
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
    public function getSmartyForeach($item = 'item', $from = 'from', $content = 'content', $name = '', $key = '', $t = '')
    {
        $name = $name != '' ? " name={$name}" : '';
        $key = $key != '' ? " key={$key}" : '';
        $ret = "{$t}<{foreach item={$item} from=\${$from}{$key}{$name}}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}<{/foreach}>\n";

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
    public function getSmartyForeachQuery($item = 'item', $from = 'from', $content = 'content', $loop = 'loop', $key = '', $t = '')
    {
        $loop = $loop != '' ? " loop={$loop}" : '';
        $key = $key != '' ? " key={$key}" : '';
        $ret = "{$t}<{foreachq item={$item} from=\${$from}{$key}{$loop}}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}<{/foreachq}>\n";

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
    public function getSmartySection($name = 'name', $loop = 'loop', $content = 'content', $start = 0, $step = 0, $t = '')
    {
        $start = $start != 0 ? " start={$start}" : '';
        $step = $step != 0 ? " step={$step}" : '';
        $ret = "{$t}<{section name={$name} loop=\${$loop}{$start}{$step}}>\n";
        $ret .= "{$t}{$content}";
        $ret .= "{$t}<{/section}>\n";

        return $ret;
    }
}

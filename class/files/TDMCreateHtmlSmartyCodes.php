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
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TDMCreateHtmlSmartyCodes.
 */
class TDMCreateHtmlSmartyCodes extends TDMCreateFile
{
    /*
    * @var string
    */
    protected $htmlcode = '';

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

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
            $ret = <<<EOT
<{$tag}{$attr} />
EOT;
        } else {
            $ret = <<<EOT
<{$tag}{$attr}>{$content}</{$tag}>
EOT;
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
    *  @param string $comment
    */
    /**
     * @param $comment
     *
     * @return string
     */
    public function getHtmlEmpty($comment = '')
    {
        $ret = <<<EOT
{$comment}
EOT;

        return $ret;
    }

    /*
    *  @public function getHtmlComment
    *  @param string $comment
    */
    /**
     * @param $comment
     *
     * @return string
     */
    public function getHtmlComment($comment = '')
    {
        $ret = <<<EOT
<!-- {$comment} -->
EOT;

        return $ret;
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
        $ret = <<<EOT
<{* {$content} *}>
EOT;

        return $ret;
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
    public function getSmartyNoSimbol($content = '')
    {
        $ret = <<<EOT
<{{$content}}>
EOT;

        return $ret;
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
        $ret = <<<EOT
<{\$smarty.const.{$language}{$const}}>
EOT;

        return $ret;
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
        $ret = <<<EOT
<{\${$var}}>
EOT;

        return $ret;
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
        $ret = <<<EOT
<{\${$leftVar}.{$rightVar}}>
EOT;

        return $ret;
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
            $ret = <<<EOT
<{include file='db:{$moduleDirname}_{$fileName}.tpl'}>
EOT;
        } elseif ($admin && !$q) {
            $ret = <<<EOT
<{include file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>
EOT;
        } elseif (!$admin && $q) {
            $ret = <<<EOT
<{includeq file='db:{$moduleDirname}_{$fileName}.tpl'}>
EOT;
        } elseif ($admin && $q) {
            $ret = <<<EOT
<{includeq file='db:{$moduleDirname}_admin_{$fileName}.tpl'}>
EOT;
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
        $ret = <<<EOT
<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}[i]}>
EOT;

        return $ret;
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
        $ret = <<<EOT
<{include file='db:{$moduleDirname}_{$fileName}_list.tpl' {$tableFieldName}=\${$tableFieldName}}>
EOT;

        return $ret;
    }

    /*
    *  @public function getSmartyConditions
    *  @param string $condition
    *  @param string $operator
    *  @param string $type
    *  @param string $contentIf
    *  @param mixed  $contentElse
    *  @param bool   $count
    * @param bool    $noSimbol
    */
    /**
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     * @param bool   $count
     * @param bool   $noSimbol
     *
     * @return string
     */
    public function getSmartyConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false, $count = false, $noSimbol = false)
    {
        $ret = '';
		if (false === $contentElse) {
            $ret .= $this->getConditions($ret, $condition, $operator, $type, $count, $noSimbol);
            $ret .= <<<EOT
	{$contentIf}
<{/if}>
EOT;
        } else {
            $ret .= $this->getConditions($ret, $condition, $operator, $type, $count, $noSimbol);
            $ret .= <<<EOT
    {$contentIf}
<{else}>
    {$contentElse}
<{/if}>
EOT;
        }

        return $ret;
    }
	
	/*
     * @private function getConditions
     *
     * @param $ret
	 * @param $condition
     * @param $operator
     * @param $type
     * @param $count
     * @param $noSimbol
     *
     * @return string
     */
    private function getConditions($ret = '', $condition = '', $operator = '', $type = '', $count = false, $noSimbol = false)
    {
			if (!$count && (false === $noSimbol)) {
                $ret .= <<<EOT
<{if \${$condition}{$operator}{$type}}>\n
EOT;
            } elseif (true === $noSimbol) {
                $ret .= <<<EOT
<{if {$condition}{$operator}{$type}}>\n
EOT;
            } elseif ($count) {
                $ret .= <<<EOT
<{if count(\${$condition}){$operator}{$type}}>\n
EOT;
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
        $ret = <<<EOT
<{foreach item={$item} from=\${$from}{$key}{$name}}>
        {$content}
<{/foreach}>
EOT;

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
    public function getSmartyForeachQuery($item = 'item', $from = 'from', $content = 'content', $name = '', $key = '')
    {
        $name = $name != '' ? " name={$name}" : '';
        $key = $key != '' ? " key={$key}" : '';
        $ret = <<<EOT
<{foreachq item={$item} from=\${$from}{$key}{$loop}}>
    {$content}
<{/foreachq}>
EOT;

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
        $ret = <<<EOT
<{section name={$name} loop=\${$loop}{$start}{$step}}>
    {$content}
<{/section}>
EOT;

        return $ret;
    }
}

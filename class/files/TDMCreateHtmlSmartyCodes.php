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
    protected $htmlcode;

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
		if(!$closed) {
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
     * @return string
     */
    private function getAttributes($attributes)
    {
        $str = '';
        foreach ($attributes as $name => $value) {
            if ($name != '_') {
                $str .= ' ' . $name . '="' . $value . '"';
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
    *  @public function getHtmlBr
    *  @param string $numb
    *  @param string $class
    */
    /**
     * @param $numb
     * @param $class
     *
     * @return string
     */
    public function getHtmlBr($numb = 1, $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = '';
        for ($i = 0; $i < $numb; ++$i) {
            $ret .= <<<EOT
<br{$class} />
EOT;
        }

        return $ret;
    }

    /*
    *  @public function getHtmlHNumb
    *  @param string $class
    *  @param string $content
    */
    /**
     * @param $content
     * @param $class
     *
     * @return string
     */
    public function getHtmlHNumb($content = '', $n = '1', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<h{$n}{$class}>{$content}</h{$n}>
EOT;

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
    public function getHtmlDiv($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<div{$class}>
    {$content}
</div>
EOT;

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
    public function getHtmlPre($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<pre{$class}>
    {$content}
</pre>
EOT;

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
    public function getHtmlSpan($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<span{$class}>{$content}</span>
EOT;

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
    public function getHtmlParagraph($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<p{$class}>
    {$content}
</p>
EOT;

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
    public function getHtmlI($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<i{$class}>{$content}</i>
EOT;

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
    public function getHtmlUl($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<ul{$class}>
    {$content}
</ul>
EOT;

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
    public function getHtmlOl($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<ol{$class}>
    {$content}
</ol>
EOT;

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
    public function getHtmlLi($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
	<li{$class}>{$content}</li>
EOT;

        return $ret;
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
    public function getHtmlStrong($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
	<strong{$class}>{$content}</strong>
EOT;

        return $ret;
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
    public function getHtmlAnchor($url = '#', $content = '&nbsp;', $title = '', $target = '', $class = '', $rel = '')
    {
        $target = ($target != '') ? " target='{$target}'" : '';
        $class = ($class != '') ? " class='{$class}'" : '';
        $rel = ($rel != '') ? " rel='{$rel}'" : '';

        $ret = <<<EOT
<a{$class} href='{$url}' title='{$title}'{$target}{$rel}>{$content}</a>
EOT;

        return $ret;
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
    public function getHtmlImage($src = 'blank.gif', $alt = 'blank.gif', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<img{$class} src='{$src}' alt='{$alt}' />
EOT;

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
    public function getHtmlTable($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
<table{$class}>
    {$content}
</table>
EOT;

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
    public function getHtmlTableThead($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
    <thead{$class}>
        {$content}
    </thead>
EOT;

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
    public function getHtmlTableTbody($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
    <tbody{$class}>
        {$content}
    </tbody>
EOT;

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
    public function getHtmlTableTfoot($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
    <tfoot{$class}>
        {$content}
    </tfoot>
EOT;

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
    public function getHtmlTableRow($content = '', $class = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $ret = <<<EOT
		<tr{$class}>
            {$content}
        </tr>
EOT;

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
    public function getHtmlTableHead($content = '', $class = '', $colspan = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';
        $ret = <<<EOT
			<th{$colspan}{$class}>{$content}</th>
EOT;

        return $ret;
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
    public function getHtmlTableData($content = '', $class = '', $colspan = '')
    {
        $class = ($class != '') ? " class='{$class}'" : '';
        $colspan = ($colspan != '') ? " colspan='{$colspan}'" : '';
        $ret = <<<EOT
			<td{$colspan}{$class}>{$content}</td>
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
                $ret = <<<EOT
<{if \${$condition}{$operator}{$type}}>\n
EOT;
            } elseif (!$noSimbol) {
                $ret = <<<EOT
<{if {$condition}{$operator}{$type}}>\n
EOT;
            } else {
                $ret = <<<EOT
<{if count(\${$condition}){$operator}{$type}}>\n
EOT;
            }
            $ret .= <<<EOT
	{$contentIf}
<{/if}>
EOT;
        } else {
            if (!$count) {
                $ret = <<<EOT
<{if \${$condition}{$operator}{$type}}>\n
EOT;
            } elseif (!$noSimbol) {
                $ret = <<<EOT
<{if {$condition}{$operator}{$type}}>\n
EOT;
            } else {
                $ret = <<<EOT
<{if count(\${$condition}){$operator}{$type}}>\n
EOT;
            }
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

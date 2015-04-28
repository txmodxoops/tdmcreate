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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: htmlsmartycodes.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TDMCreateHtmlSmartyCodes extends TDMCreateFile
{
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() { 
		parent::__construct();		
	}
	/*
	*  @static function &getInstance
	*  @param null
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
	*  @public function getHtmlDiv
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlDiv($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<div class='{$class}'>
			{$content}
		</div>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlSpan
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlSpan($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<span class='{$class}'>
			{$content}
		</span>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlParagraph
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlParagraph($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<p class='{$class}'>
			{$content}
		</p>
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
	public function getHtmlAnchor($class = 'bnone', $url = 'http://', $target = '_top', $content = '') {    
		$ret = <<<EOT
		<a class='{$class}' href='{$url}' target='{$target}'>
			{$content}
		</a>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlImage
	*  @param string $class
	*  @param string $src
	*  @param string $alt
	*/
	public function getHtmlImage($class = 'bnone', $src = 'blank.gif', $alt = 'blank.gif') {    
		$ret = <<<EOT
		<img class='{$class}' src='{$src}' alt='{$alt}' />
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlTable
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlTable($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<table class='{$class}'>
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
	public function getHtmlTableThead($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<thead class='{$class}'>
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
	public function getHtmlTableTbody($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<tbody class='{$class}'>
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
	public function getHtmlTableTfoot($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<tfoot class='{$class}'>
			{$content}
		</tfoot>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlTableHead
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlTableHead($class = 'bnone', $content = '') {		
		$ret = <<<EOT
		<th class='{$class}'>{$content}</th>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlTableRow
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlTableRow($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<tr class='{$class}'>
			{$content}
		</tr>
EOT;
		return $ret;
	}
	/*
	*  @public function getHtmlTableData
	*  @param string $class
	*  @param string $content
	*/
	public function getHtmlTableData($class = 'bnone', $content = '') {    
		$ret = <<<EOT
		<td class='{$class}'>{$content}</td>
EOT;
		return $ret;
	}
	/*
	*  @public function getSmartyConst
	*  @param string $language
	*  @param mixed $fieldName
	*/
	public function getSmartyConst($language, $fieldName) {		
		$ret = <<<EOT
		<{\$smarty.const.{$language}{$fieldName}}>
EOT;
		return $ret;
	}
	/*
	*  @public function getSmartyTableFieldNameEmptyData
	*  @param string $tableName
	*  @param string $fieldName
	*/
	public function getSmartyTableFieldNameEmptyData($tableName = '', $fieldName = '') {    
		$ret = <<<EOT
		<{\${$tableName}.{$fieldName}}>
EOT;
		return $ret;
	}
	/*
	*  @public function getSmartyTableField
	*  @param string $tableFieldname
	*  @param string $fieldName
	*/
	public function getSmartyTableFieldData($tableFieldname = '', $fieldName = '') {    
		$ret = <<<EOT
		<{\${$tableFieldname}.{$fieldName}}>
EOT;
		return $ret;
	}
	/*
	*  @public function getSmartyIncludeFile
	*  @param string $name
	*/
	public function getSmartyIncludeFile($moduleDirname, $tableName = 'header') {    
		$ret = <<<EOT
		<{include file='db:{$moduleDirname}_{$tableName}.html'}>
EOT;
		return $ret;
	}
	/*
	*  @public function getSmartyConditions
	*  @param string $condition
	*  @param string $operator
	*  @param string $type
	*  @param string $content_if
	*  @param mixed $content_else
	*/
	public function getSmartyConditions($condition = '', $operator = '==', $type = '1', $content_if = '', $content_else = false) {    
		if(!$content_else) {
			$ret = <<<EOT
			<{if ${$condition} {$operator} {$type}'}>
				{$content_if}
			<{/if}>
EOT;
		} else {
			$ret = <<<EOT
			<{if ${$condition} {$operator} {$type}'}>
				{$content_if}
			<{else}>
			    {$content_else}
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
	public function getSmartyForeach($item = 'item', $from = 'from', $content = 'content') {    
		$ret = <<<EOT
		<{foreach item={$item} from=${$from}}>
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
	public function getSmartyForeachQuery($item = 'item', $from = 'from', $content = 'content') {    
		$ret = <<<EOT
		<{foreachq item={$item} from=${$from}}>
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
	public function getSmartySection($name = 'name', $loop = 'loop', $content = 'content') {    
		$ret = <<<EOT
		<{section name={$name} loop=${$loop}}>
			{$content}
		<{/section}>
EOT;
		return $ret;
	}
}
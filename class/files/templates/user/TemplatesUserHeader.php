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
 * @version         $Id: TemplatesUserHeader.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TemplatesUserHeader extends TDMCreateHtmlSmartyCodes
{	
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() {    
		$this->tdmcfile = TDMCreateFile::getInstance();		
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
	*  @public function write
	*  @param string $module
	*  @param mixed $tables
	*  @param string $filename
	*/
	public function write($module, $tables, $filename) {    
		$this->setModule($module);
		$this->setTables($tables);
		$this->setFileName($filename);
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
		$tables = $this->getTables();        		
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname');		
        $language = $this->getLanguage($moduleDirname, 'MA');		
		$content = <<<EOT
<{if \$adv != ''}>
	<div class="center"><{\$adv}></div>
<{/if}>
<table class="{$moduleDirname}">
    <thead>          
		  <tr class="center">
			<th><{\$smarty.const.{$language}TITLE}>  -  <{\$smarty.const.{$language}DESC}></th>
          </tr>  
    </thead>
    <tbody>
        <tr class="center">
            <td class="center bold pad5">
                <ul class="menu">
					<li><a href="<{\${$moduleDirname}_url}>"><{\$smarty.const.{$language}INDEX}></a></li>\n
EOT;
		foreach (array_keys($tables) as $i)
		{	
			$tableName = $tables[$i]->getVar('table_name');
			$stu_table_name = strtoupper($tableName);
			$content .= <<<EOT
					<li> | </li>
					<li><a href="<{\${$moduleDirname}_url}>/{$tableName}.php"><{\$smarty.const.{$language}{$stu_table_name}}></a></li>\n
EOT;
		}					 
		$content .= <<<EOT
				</ul>
            </td>
        </tr>        
    </tbody>
	<tfoot>
		<{if \$adv != ''}>
			<tr class="center"><td class="center bold pad5"><{\$adv}></td></tr>
        <{else}>  
			<tr class="center"><td class="center bold pad5">&nbsp;</td></tr>
        <{/if}>
	</tfoot>
</table>
EOT;
		$this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
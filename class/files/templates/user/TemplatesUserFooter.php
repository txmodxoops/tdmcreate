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
 * @version         $Id: templates_footer.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TemplatesUserFooter extends TDMCreateHtmlSmartyCodes
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
	*  @param mixed $table
	*  @param string $filename
	*/
	public function write($module, $table, $filename) {    
		$this->setModule($module);
		$this->setTable($table);
		$this->setFileName($filename);		
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
		$table = $this->getTable();        		
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname');	
        $language = $this->getLanguage($moduleDirname, 'MA');		
		$content = <<<EOT
<{if \$bookmarks != 0}>
<{include file="db:system_bookmarks.html"}>
<{/if}>
\n<{if \$fbcomments != 0}>
<{include file="db:system_fbcomments.html"}>
<{/if}>
<div class="left"><{\$copyright}></div>\n
EOT;
		if (is_object($table)) {
			if( $table->getVar('table_name') != null ) {
				$content .= <<<EOT
<{if \$pagenav != ''}>
	<div class="right"><{\$pagenav}></div>
<{/if}>
<br />\n
EOT;
			}
		}
		$content .= <<<EOT
<{if \$xoops_isadmin}>
   <div class="center bold"><a href="<{\$admin}>"><{\$smarty.const.{$language}ADMIN}></a></div><br />
<{/if}>\n
EOT;
		if (is_object($table)) {	
			if ( $table->getVar('table_comments') == 1 ) {
				$content .= <<<EOT
<div class="pad2 marg2">
	<{if \$comment_mode == "flat"}>
		<{include file="db:system_comments_flat.html"}>
	<{elseif \$comment_mode == "thread"}>
		<{include file="db:system_comments_thread.html"}>
	<{elseif \$comment_mode == "nest"}>
		<{include file="db:system_comments_nest.html"}>
	<{/if}>
</div>\n
<br />\n
EOT;
			}
			if ( $table->getVar('table_notifications') == 1 ) {
				$content .= <<<EOT
<{include file='db:system_notification_select.html'}>
EOT;
			}
		}
		$this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
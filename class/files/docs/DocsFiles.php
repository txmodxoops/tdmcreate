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
 * @version         $Id: DocsFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class DocsFiles extends TDMCreateFile
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
	*  @param string $filename
	*/
	public function write($module, $filename) {    
		$this->setModule($module);
		$this->setFileName($filename);
	}	
	/*
	*  @public function getChangeLogFile
	*  @param string $moduleDirname
	*  @param string $mod_version
	*  @param string $mod_author
	*/
	public function getChangeLogFile($moduleDirname, $mod_version, $mod_author) {    
		$date = date('Y/m/d G:i:s');		
		$ret = <<<EOT
====================================
 {$date} Version {$mod_version}
====================================
 - Original release {$moduleDirname} ({$mod_author})
EOT;
		return $ret;
	}
	/*
	*  @public function getCreditsFile
	*  @param null
	*/
	public function getCreditsFile($mod_author, $mod_credits, $mod_author_website_url, $mod_description) { 		
		$ret = <<<EOT
Read Me First
=============

Originally created by the {$mod_author}.

Modified by {$mod_credits} ({$mod_author_website_url})

Contributors: {$mod_credits} ({$mod_author_website_url})

{$mod_description}
EOT;
		return $ret;
	}
	/*
	*  @public function getInstallFile
	*  @param null
	*/
	public function getInstallFile() {    
		$ret = <<<EOT
Read Me First
=============

Install just like another XOOPS module
EOT;
		return $ret;
	}
	/*
	*  @public function getReadmeFile
	*  @param null
	*/
	public function getReadmeFile() {    
		$ret = <<<EOT
Read Me First
=============

Please make sure that you download the XOOPS Icon Set, and upload it to uploads/images directory
Read the table in admin help for the accurate description of the functionality of this module
EOT;
		return $ret;
	}
	/*
	*  @public function getLangDiffFile
	*  @param null
	*/
	public function getLangDiffFile($mod_version) {    
		
		$ret = <<<EOT
List of added language defines
=============

// {$mod_version}
EOT;
		return $ret;
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
        $module = $this->getModule();
		$moduleDirname = $module->getVar('mod_dirname');
		$mod_author = $module->getVar('mod_author');
		$mod_credits = $module->getVar('mod_credits');
		$mod_author_website_url = $module->getVar('mod_author_website_url');
		$mod_description = $module->getVar('mod_description');
		switch($filename = $this->getFileName()) {
			case 'changelog':
				$content = $this->getChangeLogFile($moduleDirname, $mod_version, $mod_author);
			break;
			case 'credits':
				$content = $this->getCreditsFile($mod_author, $mod_credits, $mod_author_website_url, $mod_description);
			break;
			case 'install':
				$content = $this->getInstallFile();
			break;
			case 'readme':
				$content = $this->getReadmeFile();
			break;
			case 'lang_diff':
				$content = $this->getLangDiffFile($mod_version);
			break;
		}
		$this->tdmcfile->create($moduleDirname, 'docs', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
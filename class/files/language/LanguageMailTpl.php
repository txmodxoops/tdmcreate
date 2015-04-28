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
 * @version         $Id: 1.91 LanguageMailTpl.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class LanguageMailTpl extends TDMCreateFile
{	
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() {
		parent::__construct();
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
	*/
	public function write($module) {    
		$this->setModule($module);
	}
	/*
	*  @public function renderFile
	*  @param string $filename
	*/
	public function renderFile($filename) { 		
		$module = $this->getModule();
		$moduleDirname = $module->getVar('mod_dirname');
		$content = <<<EOT
// ---------- Templates Mail Content Dummy ---------- //
Hello {X_UNAME},

A new story "{STORY_NAME}" has been added at {X_SITENAME}.

You can view this story here:
{STORY_URL}

-----------

You are receiving this message because you selected to be notified when new stories are added to our site.

If this is an error or you wish not to receive further such notifications, please update your subscriptions by visiting the link below:
{X_UNSUBSCRIBE_URL}

Please do not reply to this message.

-----------

{X_SITENAME} ({X_SITEURL})
webmaster
{X_ADMINMAIL}
EOT;
		$this->tdmcfile->create($moduleDirname, 'language/english/mail_template', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
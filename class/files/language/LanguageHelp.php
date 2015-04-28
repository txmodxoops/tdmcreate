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
 * @version         $Id: 1.91 LanguageHelp.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class LanguageHelp extends TDMCreateFile
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
	*  @public function render
	*  @param null
	*/
	public function render() { 		
		$module = $this->getModule();
		$filename = $this->getFileName();
		$moduleName = $module->getVar('mod_name');
		$moduleDirname = $module->getVar('mod_dirname');
		$language = $GLOBALS['xoopsConfig']['language'];
		$content = <<<EOT
<div id="help-template" class="outer">
    <h1 class="head">Help:
        <a class="ui-corner-all tooltip" href="<{\$xoops_url}>/modules/{$moduleDirname}/admin/index.php"
           title="Back to the administration of {$moduleName}"> {$moduleName} <img src="<{xoAdminIcons home.png}>"
                                                                       alt="Back to the Administration of {$moduleName}"/>
        </a></h1>
    <!-- -----Help Content ---------- -->
    <h4 class="odd">Description</h4>
    <p class="even">
        The {$moduleName} module can be used to modules in XOOPS<br /><br />
    </p> 
    <h4 class="odd">Install/uninstall</h4>
    <p class="even">
No special measures necessary, follow the standard installation process and extract the {$moduleDirname} folder into the ../modules directory. Install the module through Admin -> System Module -> Modules. <br /><br />
Detailed instructions on installing modules are available in the <a href="http://goo.gl/adT2i">XOOPS Operations Manual</a> 
    </p>
	<h4 class="odd">Features</h4>
    <p class="even">
        The TDMCreate module continues to expand, to get to the conditions to create modules, more and more sophisticated.<br />
        For this reason, I invite all developers to report and send in svn any changes or additions to this module, so that we can jointly contribute to the development <br /><br />
    </p>
    <h4 class="odd">Tutorial</h4>
    <p class="even">
        You can find a more detailed to this Video Tutorial <a href="http://www.youtube.com/watch?v=dg7zGFCopxY" rel="external">here</a>
    </p>
    <!-- -----Help Content ---------- -->
</div>
EOT;
		if($language != 'english' ) {	
			$this->tdmcfile->create($moduleDirname, 'language/'.$language.'/help', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		}
		$this->tdmcfile->create($moduleDirname, 'language/english/help', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
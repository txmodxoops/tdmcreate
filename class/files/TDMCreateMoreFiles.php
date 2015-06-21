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
 * @version         $Id: TDMCreateMoreFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreateMoreFiles
 */
class TDMCreateMoreFiles extends TDMCreateFile
{
    //
	private $folder;
	 //
	private $extension;
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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateMoreFiles
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
    /**
     * @param $module
	 * @param $filename
     * @return string
     */
    public function write($module, $filename, $folder, $extension)
    {
        $this->setModule($module);
		$this->extension = $extension;
		$this->setFileName($filename . '.' . $extension);
		if(strstr($folder, 'user')){
			$this->folder = '/';
		} else {
			$this->folder = $folder;
		}
    }
    
    /*
    *  @private function getMoreFilesFilePhp
    *  @param $header
    */
    /**
     * @param $header
     * @return string
     */
    private function getMoreFilesFilePhp($header)
    {
		$ret = <<<EOT
<?php
{$header}\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileTpl
    *  @param $header
    */
    /**
     * @param $header
     * @return string
     */
    private function getMoreFilesFileTpl()
    {
		$ret = <<<EOT
<div class="panel">
	Pleace! put your template code here
</div>\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileHtml
    *  @param $header
    */
    /**
     * @param $header
     * @return string
     */
    private function getMoreFilesFileHtml()
    {
		$ret = <<<EOT
<div class="panel">
	Pleace! put your Html code here
</div>\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileText
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getMoreFilesFileText()
    {
		$ret = <<<EOT
Pleace! put your text code here\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileSql
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getMoreFilesFileSql()
    {
		$ret = <<<EOT
Pleace! put your sql code here\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileCss
    *  @param $header
    */
    /**
     * @param $header
     * @return string
     */
    private function getMoreFilesFileCss($header)
    {
		$ret = <<<EOT
@charset "UTF-8";
{$header}\n\nPleace! put your sql code here\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getMoreFilesFileDefault
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getMoreFilesFileDefault()
    {
		$ret = <<<EOT
\n
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param string $filename
    */
    /**
     * @param $filename
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
		$filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
		$header        = $this->getHeaderFilesComments($module, $filename, 0);
        switch($this->extension) {
			case 'php':
				$content = $this->getMoreFilesFilePhp($header);
				break;
			case 'tpl':
				$content = $this->getMoreFilesFileTpl();
				break;
			case 'html':
				$content = $this->getMoreFilesFileHtml();
				break;
			case 'text':
				$content = $this->getMoreFilesFileText();
				break;
			case 'sql':
				$content = $this->getMoreFilesFileSql();
				break;
			case 'css':
				$content = $this->getMoreFilesFileCss($header);
				break;
			default:
				$content = $this->getMoreFilesFileDefault();
				break;
		}
		
        //
        $this->tdmcfile->create($moduleDirname, $this->folder, $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

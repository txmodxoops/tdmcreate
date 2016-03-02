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
 * @version         $Id: TemplatesUserMoreFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserMoreFiles.
 */
class TemplatesUserMoreFiles extends TDMCreateFile
{
    //
    private $folder = null;
    //
    private $extension = null;
    /*
    * @var string
    */
    private $tdmcfile = null;

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
     * @return TemplatesUserMoreFiles
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
     *
     * @return string
     */
    public function write($module, $folder = '', $filename, $extension)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        if ($folder != '') {
            $this->folder = 'templates/'.$folder;
        } else {
            $this->folder = 'templates';
        }
        $this->extension = $extension;
    }

    /*
    *  @private function getTemplatesUserMoreFile
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getTemplatesUserMoreFile()
    {
        $ret = <<<EOT
<div class="panel">
	Pleace! Enter here your template code here
</div>
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getTemplatesUserMoreFile();
        //
        $this->tdmcfile->create($moduleDirname, $this->folder, $filename.'.'.$this->extension, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

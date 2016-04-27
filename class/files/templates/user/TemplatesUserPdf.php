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
 * @version         $Id: TemplatesUserPdf.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserPdf.
 */
class TemplatesUserPdf extends TDMCreateFile
{
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
     * @return TemplatesUserPdf
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
    *  @public function write
     * @param $module
     */
    public function write($module)
    {
        $this->setModule($module);
    }

    /**
    *  @private function getTemplatesUserPdfBody
     * @param null
     *
     * @return string
     */
    private function getTemplatesUserPdfBody()
    {
        $ret = <<<EOT
<div><{\$pdfoutput}></div>
EOT;

        return $ret;
    }

    /**
    *  @public function renderFile
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getTemplatesUserPdfBody();
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

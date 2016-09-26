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
 * @version         $Id: TemplatesUserSingle.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserSingle.
 */
class TemplatesUserSingle extends TDMCreateFile
{
    /*
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
        parent::__construct();
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserSingle
     */
    public static function getInstance()
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
    *  @param string $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @private function getTemplatesUserSingleHeader
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserSingleHeader($moduleDirname)
    {
        $smarty = TDMCreateSmartyCode::getInstance();
        $ret = $smarty->getSmartyIncludeFile($moduleDirname);

        return $ret;
    }

    /*
    *  @private function getTemplatesUserSingleBody
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserSingleBody($moduleDirname, $table, $language)
    {
        $tableName = $table->getVar('table_name');
        $hc = TDMCreateHtmlCode::getInstance();
        $t = "\t";
        $ret = '';
        $content = $hc->getHtmlHNumb('Services Panels', '2', 'page-header', $t."\t");
        $collg12 = $hc->getHtmlDiv($content, 'col-lg-12', $t);
        $row = $hc->getHtmlDiv($collg12, 'row', $t);
        $ret .= $hc->getHtmlDiv($row, 'container');

        return $ret;
    }

    /*
    *  @private function getTemplatesUserSingleFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserSingleFooter($moduleDirname)
    {
        $smarty = TDMCreateSmartyCode::getInstance();
        $ret = $smarty->getSmartyIncludeFile($moduleDirname, 'footer');

        return $ret;
    }

    /*
    *  @public function render
    *  @param string $filename
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserSingleHeader($moduleDirname);
        $content .= $this->getTemplatesUserSingleBody($moduleDirname, $table, $language);
        $content .= $this->getTemplatesUserSingleFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

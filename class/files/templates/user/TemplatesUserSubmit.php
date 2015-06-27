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
 * @version         $Id: TemplatesUserSubmit.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserSubmit.
 */
class TemplatesUserSubmit extends TDMCreateHtmlSmartyCodes
{
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
        $this->htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserSubmit
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
    *  @param string $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     */
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getTemplatesUserSubmitHeader
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
    private function getTemplatesUserSubmitHeader($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header').PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserSubmit
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserSubmit($moduleDirname, $language)
    {        
        $const  = $this->htmlcode->getSmartyConst($language, 'SUBMIT_SUBMITONCE');
		$li     = $this->htmlcode->getHtmlLi($const).PHP_EOL;
		$const  = $this->htmlcode->getSmartyConst($language, 'SUBMIT_ALLPENDING');
		$li    .= $this->htmlcode->getHtmlLi($const).PHP_EOL;
		$const  = $this->htmlcode->getSmartyConst($language, 'SUBMIT_DONTABUSE');
		$li    .= $this->htmlcode->getHtmlLi($const).PHP_EOL;
		$const  = $this->htmlcode->getSmartyConst($language, 'SUBMIT_TAKEDAYS');
		$li    .= $this->htmlcode->getHtmlLi($const).PHP_EOL;
        $ul     = $this->htmlcode->getHtmlUl($li).PHP_EOL;
		$ret    = $this->htmlcode->getHtmlDiv($ul, $moduleDirname.'-tips').PHP_EOL;
		
		$single   = $this->htmlcode->getSmartySingleVar('message_error').PHP_EOL;
		$divError = $this->htmlcode->getHtmlDiv($single, 'errorMsg').PHP_EOL;
		$ret   .= $this->htmlcode->getSmartyConditions('message_error', ' != ', '\'\'', $divError).PHP_EOL;
		$single = $this->htmlcode->getSmartySingleVar('form').PHP_EOL;
		$ret   .= $this->htmlcode->getHtmlDiv($single, $moduleDirname.'-submitform').PHP_EOL;
		
		return $ret;
    }

    /*
    *  @private function getTemplatesUserSubmitFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserSubmitFooter($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'footer');
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
    public function renderFile($filename)
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MA');
        $content        = $this->getTemplatesUserSubmitHeader($moduleDirname);
        $content       .= $this->getTemplatesUserSubmit($moduleDirname, $language);
        $content       .= $this->getTemplatesUserSubmitFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

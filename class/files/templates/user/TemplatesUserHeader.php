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
 * @version         $Id: TemplatesUserHeader.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserHeader.
 */
class TemplatesUserHeader extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserHeader
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
    /**
     * @param $module
     * @param $tables
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @public function getTemplatesUserHeader
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplatesUserHeader($moduleDirname)
    {
        $ret = $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'breadcrumbs', false, true).PHP_EOL;
        $var = $this->htmlcode->getSmartySingleVar('ads');
        $div = $this->htmlcode->getHtmlDiv($var, 'center').PHP_EOL;
        $ret .= $this->htmlcode->getSmartyConditions('ads', ' != ', '\'\'', $div).PHP_EOL;

        return $ret;
    }

    /*
     *  @public function getTemplateFooterFacebbokSDK
     *  @param null
     *
     * @return bool|string
     */
    public function getTemplateUserHeaderFacebbokSDK()
    {
        $ret = <<<EOT
	<!-- Load Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        //$language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserHeader($moduleDirname);

        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

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
 * @version         $Id: user_footer.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserFooter.
 */
class UserFooter extends TDMCreateFile
{
    /*
    * @var string
    */
    private $xc = null;

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
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserFooter
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
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @private function getUserFooter
    *  @param $moduleDirname
    *
    *  @return string
    */
    private function getUserFooter($moduleDirname)
    {
        $xoBreadcrumbs = $this->xc->getXcTplAssign('xoBreadcrumbs', '$xoBreadcrumbs', true, "\t");
        $ret = $this->phpcode->getPhpCodeConditions('count($xoBreadcrumbs)', ' > ', '1', $xoBreadcrumbs);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $ret .= $this->xc->getXcTplAssign('adv', "\${$moduleDirname}->getConfig('advertise')");
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcTplAssign('bookmarks', "\${$moduleDirname}->getConfig('bookmarks')");
        $ret .= $this->xc->getXcTplAssign('fbcomments', "\${$moduleDirname}->getConfig('fbcomments')");
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcTplAssign('admin', "{$language}ADMIN");
        $ret .= $this->xc->getXcTplAssign('copyright', '$copyright');
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'footer', true);

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserFooter($moduleDirname);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

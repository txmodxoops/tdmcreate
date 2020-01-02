<?php namespace XoopsModules\Tdmcreate\Files\User;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 * @copyright       XOOPS Project (https://xoops.org)
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
class UserFooter extends Files\CreateFile
{
    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return UserFooter
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     *  @public function write
     *  @param string $module
     *  @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     *  @private function getUserFooter
     *  @param $moduleDirname
     *
     *  @return string
     */
    private function getUserFooter($moduleDirname)
    {
        $xc = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc = Tdmcreate\Files\CreatePhpCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $xoBreadcrumbs = $xc->getXcTplAssign('xoBreadcrumbs', '$xoBreadcrumbs', true, "\t");
        $ret = $pc->getPhpCodeConditions('count($xoBreadcrumbs)', ' > ', '1', $xoBreadcrumbs);
        $ret .= $xc->getXcTplAssign('adv', "\${$moduleDirname}->getConfig('advertise')");
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcTplAssign('bookmarks', "\${$moduleDirname}->getConfig('bookmarks')");
        $ret .= $xc->getXcTplAssign('fbcomments', "\${$moduleDirname}->getConfig('fbcomments')");
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcTplAssign('admin', "{$stuModuleDirname}_ADMIN");
        $ret .= $xc->getXcTplAssign('copyright', '$copyright');
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'footer', true);

        return $ret;
    }

    /**
     *  @public function render
     *  @param null
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

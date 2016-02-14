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
 * @version         $Id: 1.91 LanguageMailTpl.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class LanguageMailTpl.
 */
class LanguageMailTpl extends TDMCreateFile
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
        $this->defines = LanguageDefines::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return LanguageMailTpl
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
     * @public function write
     *
     * @param $module
     * @param $filename
     * @param $module
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
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
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
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
        $this->create($moduleDirname, 'language/'.$GLOBALS['xoopsConfig']['language'].'/mail_template', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

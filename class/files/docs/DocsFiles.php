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
 * @version         $Id: DocsFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class DocsFiles.
 */
class DocsFiles extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return DocsFiles
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
    *  @public function getChangeLogFile
    *  @param string $moduleDirname
    *  @param string $mod_version
    *  @param string $mod_author
    */
    /**
     * @param $moduleDirname
     * @param $mod_version
     * @param $mod_author
     *
     * @return string
     */
    public function getChangeLogFile($moduleDirname, $modVersion, $modAuthor)
    {
        $date = date('Y/m/d G:i:s');
        $ret = <<<EOT
====================================
 {$date} Version {$modVersion}
====================================
 - Original release {$moduleDirname} ({$modAuthor})
EOT;

        return $ret;
    }

    /*
    *  @public function getCreditsFile
    *  @param null
    */
    /**
     * @param $mod_author
     * @param $mod_credits
     * @param $mod_author_website_url
     * @param $mod_description
     *
     * @return string
     */
    public function getCreditsFile($modAuthor, $modCredits, $modAuthorWebsiteUrl, $modDescription)
    {
        $ret = <<<EOT
Read Me First
=============

Originally created by the {$modAuthor}.

Modified by {$modCredits} ({$modAuthorWebsiteUrl})

Contributors: {$modCredits} ({$modAuthorWebsiteUrl})

{$modDescription}
EOT;

        return $ret;
    }

    /*
    *  @public function getInstallFile
    *  @param null
    */
    /**
     * @return string
     */
    public function getInstallFile()
    {
        $ret = <<<EOT
Read Me First
=============

Install just like another XOOPS module
EOT;

        return $ret;
    }

    /*
    *  @public function getReadmeFile
    *  @param null
    */
    /**
     * @return string
     */
    public function getReadmeFile()
    {
        $ret = <<<EOT
Read Me First
=============

Please make sure that you download the XOOPS Icon Set, and upload it to uploads/images directory
Read the table in admin help for the accurate description of the functionality of this module
EOT;

        return $ret;
    }

    /*
    *  @public function getLangDiffFile
    *  @param null
    */
    /**
     * @param $modVersion
     *
     * @return string
     */
    public function getLangDiffFile($modVersion)
    {
        $ret = <<<EOT
Legend :
+ Added
- Removed
* Modified

To see the differences of language files, see on language folder

// {$modVersion}
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
        $moduleDirname = $module->getVar('mod_dirname');
        $modAuthor = $module->getVar('mod_author');
        $modCredits = $module->getVar('mod_credits');
        $modAuthorWebsiteUrl = $module->getVar('mod_author_website_url');
        $modDescription = $module->getVar('mod_description');
        switch ($filename = $this->getFileName()) {
            case 'changelog':
                $content = $this->getChangeLogFile($moduleDirname, $modVersion, $modAuthor);
                break;
            case 'credits':
                $content = $this->getCreditsFile($modAuthor, $modCredits, $modAuthorWebsiteUrl, $modDescription);
                break;
            case 'install':
                $content = $this->getInstallFile();
                break;
            case 'readme':
                $content = $this->getReadmeFile();
                break;
            case 'lang':
                $content = $this->getLangDiffFile($modVersion);
                break;
        }
        $this->tdmcfile->create($moduleDirname, 'docs', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

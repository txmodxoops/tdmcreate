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
 * @version         $Id: TDMCreateMoreFiles.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TDMCreateMoreFiles.
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
     *
     * @return string
     */
    public function write($module, $filename, $folder, $extension)
    {
        $this->setModule($module);
        $this->extension = $extension;
        $this->setFileName($filename.'.'.$extension);
        if (strstr($folder, 'user')) {
            $this->folder = '/';
        } else {
            $this->folder = $folder;
        }
    }

    /*
    *  @private function getMoreFilesPhp
    *  @param $header
    */
    /**
     * @param $header
     *
     * @return string
     */
    private function getMoreFilesPhp($header)
    {
        $ret = <<<EOT
<?php
{$header}\n
EOT;
        $ret .= $this->getInclude();
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /*
    *  @private function getMoreFilesTpl
    *  @param $header
    */
    /**
     * @param $header
     *
     * @return string
     */
    private function getMoreFilesTpl()
    {
        $ret = <<<EOT
<div class="panel">
	Pleace! put your template code here
</div>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getMoreFilesHtml
    *  @param $header
    */
    /**
     * @param $header
     *
     * @return string
     */
    private function getMoreFilesHtml()
    {
        $ret = <<<EOT
<div class="panel">
	Pleace! put your Html code here
</div>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getMoreFilesText
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getMoreFilesText()
    {
        $ret = <<<EOT
# Pleace! put your text code here\n
EOT;

        return $ret;
    }

    /*
    *  @private function getMoreFilesSql
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getMoreFilesSql()
    {
        $ret = <<<EOT
# Pleace! put your sql code here\n
EOT;

        return $ret;
    }

    /*
    *  @private function getMoreFilesCss
    *  @param $header
    */
    /**
     * @param $header
     *
     * @return string
     */
    private function getMoreFilesCss($header)
    {
        $ret = <<<EOT
@charset "UTF-8";
{$header}\n\nPleace! put your sql code here\n
EOT;

        return $ret;
    }

    /*
    *  @private function getMoreFilesDefault
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getMoreFilesDefault()
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
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $header = $this->getHeaderFilesComments($module, $filename, 0);
        switch ($this->extension) {
            case 'php':
                $content = $this->getMoreFilesPhp($header);
                break;
            case 'tpl':
                $content = $this->getMoreFilesTpl();
                break;
            case 'html':
                $content = $this->getMoreFilesHtml();
                break;
            case 'text':
                $content = $this->getMoreFilesText();
                break;
            case 'sql':
                $content = $this->getMoreFilesSql();
                break;
            case 'css':
                $content = $this->getMoreFilesCss($header);
                break;
            default:
                $content = $this->getMoreFilesDefault();
                break;
        }

        $this->create($moduleDirname, $this->folder, $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

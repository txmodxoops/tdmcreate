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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: TDMCreateFile.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

xoops_load('XoopsFile');

/**
 * Class TDMCreateFile
 */
class TDMCreateFile extends TDMCreateTableFields
{
    /*
    * @var string
    */
    private $xoopsFile;

    /**
     * "filename" attribute of the files
     *
     * @var mixed
     */
    private $fileName;

    /**
     * "path" attribute of the files
     *
     * @var string
     */
    private $path;

    /**
     * "uploadPath" attribute of the files
     *
     * @var string
     */
    private $uploadPath;

    /**
     * "folder_name" attribute of the files
     *
     * @var string
     */
    private $folderName;

    /*
    * @var string
    */
    private $content;

    /*
    * @var mixed
    */
    private $created = false;

    /*
    * @var mixed
    */
    private $notCreated = false;

    /*
    * @var string
    */
    private $mode;

    /*
    * @var string
    */
    protected $tdmcfile;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->xoopsFile = XoopsFile::getHandler();
        $this->tdmcreate = TDMCreateHelper::getInstance();
    }

    /*
    *  @public static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateFile
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
     * @public function create
     *  
     * @param $moduleDirname
     * @param $subdir
     * @param $fileName
     * @param $content
     * @param $created
     * @param $notCreated
     * @param $mode
     */
    public function create($moduleDirname, $subdir = null, $fileName, $content = '', $created = false, $notCreated = false, $mode = 'w+')
    {
        $this->setFileName($fileName);
        $this->created    = $created;
        $this->notCreated = $notCreated;
        $this->setMode($mode);
        $this->setRepositoryPath($moduleDirname);
        if (!empty($content) && is_string($content)) {
            $this->setContent($content);
        }
        if (isset($subdir) && is_string($subdir)) {
            $this->setSubDir($subdir);
        }
    }

    /*
    *  @private function setPath
    *  @param string $folderName
    */
    /**
     * @param $folderName
     */
    private function setPath($folderName)
    {
        $this->path = TDMC_PATH . DIRECTORY_SEPARATOR . $folderName;
    }

    /*
    *  @private function getPath
    *  @param null
    */
    /**
     * @return string
     */
    private function getPath()
    {
        return $this->path;
    }

    /*
    *  @private function setRepositoryPath
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     */
    private function setRepositoryPath($moduleDirname)
    {
        $this->uploadPath = TDMC_UPLOAD_REPOSITORY_PATH . DIRECTORY_SEPARATOR . $moduleDirname;
    }

    /*
    *  @private function getRepositoryPath
    *  @param null
    */
    /**
     * @return string
     */
    private function getRepositoryPath()
    {
        return $this->uploadPath;
    }

    /*
    *  @private function setSubDir
    *  @param string $subdir
    */
    /**
     * @param $subdir
     */
    private function setSubDir($subdir)
    {
        $this->subdir = $subdir;
    }

    /*
    *  @private function getSubDir
    *  @param null
    */
    private function getSubDir()
    {
        return $this->subdir;
    }

    /**
     * public function setFileName
     * @param $fileName
     * @internal param mixed $file_name
     */
    public function setFileName($fileName)
    {
        $this->filename = $fileName;
    }

    /*
    *  @public function getFileName
    *  @param null
    */
    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /*
    *  @private function setContent
    *  @param string $content
    */
    /**
     * @param $content
     */
    private function setContent($content)
    {
        $this->content = $content;
    }

    /*
    *  @private function setContent
    *  @param null
    */
    /**
     * @return null
     */
    private function getContent()
    {
        return $this->content;
    }

    /*
    *  @private function getFolderName
    *  @param null
    */
    /**
     * @return string
     */
    private function getFolderName()
    {
        $path = $this->getUploadPath();
        if (strrpos($path, '\\')) {
            $str = strrpos($path, '\\');
            if ($str !== false) {
                return substr($path, $str + 1, strlen($path));
            } else {
                return substr($path, $str, strlen($path));
            }
        }
        //return 'root module';
        return null;
    }

    /*
    *  @public function getUploadPath
    *  @param null
    */
    /**
     * @return string
     */
    private function getUploadPath()
    {
        if ($this->getSubDir() != null) {
            $ret = $this->getRepositoryPath() . DIRECTORY_SEPARATOR . $this->getSubDir();
        } else {
            $ret = $this->getRepositoryPath();
        }

        return $ret;
    }

    /*
    *  @private function getCreated
    *  @param null
    */
    /**
     * @return bool
     */
    private function getCreated()
    {
        return $this->created;
    }

    /*
    *  @private function getNotCreated
    *  @param null
    */
    /**
     * @return bool
     */
    private function getNotCreated()
    {
        return $this->notCreated;
    }

    /*
    *  @private function setMode
    *  @param string $mode
    */
    /**
     * @param $mode
     */
    private function setMode($mode)
    {
        $this->mode = $mode;
    }

    /*
    *  @private function getMode
    *  @param null
    */
    /**
     * @return null
     */
    private function getMode()
    {
        return $this->mode;
    }

    /*
    *  @public function getLanguage
    *  @param string $moduleDirname
    *  @param string $prefix
    *  @param string $suffix
    */
    /**
     * @param        $moduleDirname
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function getLanguage($moduleDirname, $prefix = '', $suffix = '')
    {
        $lang = '_' . $prefix . '_' . strtoupper($moduleDirname);
        $ret = $lang;
        if (!empty($suffix) || $suffix != '_') {
            $ret = $lang . '_' . $suffix;
        } elseif ($suffix == '_') {
            $ret = $lang . '_';
        }

        return $ret;
    }

    /*
    *  @public function getRightString
    *  @param string $string
    */
    /**
     * @param $string
     * @return string
     */
    public function getRightString($string)
    {
        if (strpos($string, '_')) {
            $str = strpos($string, '_');
            if ($str !== false) {
                $ret = substr($string, $str + 1, strlen($string));

                return $ret;
            }
        }

        return $string;
    }

    /*
    *  @public function getHeaderFilesComments
    *  @param string $module
    *  @param string $fileName
    */
    /**
     * @param $module
     * @param $fileName
     * @return string
     */
    public function getHeaderFilesComments($module, $fileName)
    {
        $name               = $module->getVar('mod_name');
        $dirname            = $module->getVar('mod_dirname');
        $version            = $module->getVar('mod_version');
        $since              = $module->getVar('mod_since');
        $min_xoops          = $module->getVar('mod_min_xoops');
        $author             = $module->getVar('mod_author');
        $credits            = $module->getVar('mod_credits');
        $author_mail        = $module->getVar('mod_author_mail');
        $author_website_url = $module->getVar('mod_author_website_url');
        $license            = $module->getVar('mod_license');
        $subversion         = $module->getVar('mod_subversion');
        $date               = date('D Y/m/d G:i:s');
        $ret                = <<<EOT
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
 * {$name} module for xoops
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         {$license}
 * @package         {$dirname}
 * @since           {$since}
 * @min_xoops       {$min_xoops}
 * @author          {$author} - Email:<{$author_mail}> - Website:<{$author_website_url}>
 * @version         \$Id: {$version} {$fileName} {$subversion} {$date}Z {$credits} \$
 */\n
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param null
    */
    /**
     * @return string
     */
    public function renderFile()
    {
        $fileName   = $this->getFileName();
        $path       = $this->getUploadPath() . DIRECTORY_SEPARATOR . $fileName;
        $created    = $this->getCreated();
        $notCreated = $this->getNotCreated();
        $folderName = $this->getFolderName();
        $mode       = $this->getMode();
        $ret        = '';
        if (!$this->xoopsFile->__construct($path, true)) {
            // Force to create file if not exist
            if ($this->xoopsFile->open($mode, true)) {
                if ($this->xoopsFile->writable()) {
                    // Integration of the content in the file
                    if (!$this->xoopsFile->write($this->getContent(), $mode, true)) {
                        $ret .= sprintf($notCreated, $fileName, $folderName);
                        $GLOBALS['xoopsTpl']->assign('created', false);
                        exit();
                    }
                    // Created
                    $ret .= sprintf($created, $fileName, $folderName);
                    $GLOBALS['xoopsTpl']->assign('created', true);
                    $this->xoopsFile->close();
                } else {
                    $ret .= sprintf($notCreated, $fileName, $folderName);
                    $GLOBALS['xoopsTpl']->assign('created', false);
                }
            } else {
                $ret .= sprintf($notCreated, $fileName, $folderName);
                $GLOBALS['xoopsTpl']->assign('created', false);
            }
        }

        return $ret;
    }
}

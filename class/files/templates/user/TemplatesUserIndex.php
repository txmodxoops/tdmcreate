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
 * @version         $Id: templates_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserIndex
 */
class TemplatesUserIndex extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserIndex
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
    *  @public function getTemplateUserIndexHeader
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexHeader($moduleDirname)
    {
        $ret = <<<EOT
<{include file="db:{$moduleDirname}_header.tpl"}>
EOT;

        return $ret;
    }
	
	/*
    *  @public function getTemplateUserIndexTable
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexTable($moduleDirname, $language)
    {
        $ret = <<<EOT
<table class="outer {$moduleDirname}">
    <tbody>
		<tr class="left">
			<td class="pad5"><{\$smarty.const.{$language}INDEX_DESC}></td>
		</tr>
    </tbody>
</table>
EOT;
       
        return $ret;
    }
	
	/*
    *  @public function getTemplateUserIndexFooter
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexFooter($moduleDirname)
    {
        $ret = <<<EOT
<{include file="db:{$moduleDirname}_footer.tpl"}>
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
        $module        = $this->getModule();
		$table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
		$tableCategory = $module->getVar('table_category');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplateUserIndexHeader($moduleDirname);
		$content      .= $this->getTemplateUserIndexTable($moduleDirname, $language);
		$content      .= $this->getTemplateUserIndexFooter($moduleDirname);
		
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

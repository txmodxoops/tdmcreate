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
 * @version         $Id: user_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserIndex
 */
class UserIndex extends UserObjects
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
        $this->tdmcfile    = TDMCreateFile::getInstance();
        $this->userobjects = UserObjects::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserIndex
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
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
		$this->setTable($table);
        $this->setFileName($filename);
    }
	
	/**
     * @private function getIncludeHeaderFile
     *     
     * @param null
	 * @return string
     */
    private function getIncludeHeaderFile()
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';\n
EOT;

		return $ret;
	}
	
	/**
     * @private function getTemplateHeaderFile
     *     
     * @param $moduleDirname
	 * @return string
     */
    private function getTemplateHeaderFile($moduleDirname)
    {
        $ret = <<<EOT
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_index.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
// Define Stylesheet
\$GLOBALS['xoTheme']->addStylesheet( \$style );\n
EOT;

		return $ret;
	}
	
	/**
     * @private function getBodyIndex
     *     
     * @param $moduleDirname
	 * @param $language
	 * @return string
     */
    private function getBodyIndex($moduleDirname, $table, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
		$tableName        = $table->getVar('table_name');
		$tableSoleName    = $table->getVar('table_solename');
        $tableFieldname   = $table->getVar('table_fieldname');
		$tableCategory    = $table->getVar('table_category');
		$ucfTableName     = ucfirst($tableName);
		// Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName; // fieldMain = fields parameters main field
            }
			if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName; // fieldMain = fields parameters main field
            }
			if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
		$ret = '';
		if(1 == $tableCategory) {
			$ret .= <<<EOT
//
\${$tableName} = {$moduleDirname}_get{$ucfTableName}Ids('{$moduleDirname}_view', '{$moduleDirname}');\n
EOT;
		}
		$ret .= <<<EOT
// 
\$criteria = new CriteriaCompo();\n
EOT;
		if(strstr($fieldName, 'status')) {
			$ret .= <<<EOT
\$criteria->add(new Criteria('{$fieldName}', 0, '!='));\n
EOT;
		}		
		if(1 == $tableCategory) {
			$ret .= <<<EOT
\$criteria->add(new Criteria('{$fieldId}', '(' . implode(',', \${$tableName}) . ')','IN'));
EOT;
		}		
		$ret .= <<<EOT
\${$tableName}Count = \${$tableName}Handler->getCount(\$criteria);
\${$tableName}All   = \${$tableName}Handler->getAll(\$criteria);
\$GLOBALS['xoopsTpl']->assign('lang_thereare', sprintf(_MD_{$stuModuleDirname}_INDEX_THEREARE, \${$tableName}Count));
unset(\$criteria);\n
EOT;
		if(1 == $tableCategory) {
			$ret .= <<<EOT
\$criteria = new CriteriaCompo();
\$criteria->setSort('{$fieldId} ASC, {$fieldMain}');
\$criteria->setOrder('ASC');
\$criteria->add(new Criteria('{$fieldId}', '(' . implode(',', \${$tableName}) . ')','IN'));
\${$tableName}All = \${$tableName}Handler->getAll(\$criteria);
//
\$mytree = new XoopsObjectTree(\${$tableName}All, '{$fieldId}', '{$fieldParent}');\n
EOT;
		}
		$ret .= <<<EOT
\$GLOBALS['xoopsTpl']->assign('numb_col', \${$moduleDirname}->getConfig('numb_col'));
\$count = 1;\n
EOT;

		return $ret;
	}
	
	/**
     * @private function getDefaultFunctions
     *     
     * @param $moduleDirname
	 * @param $language
	 * @return string
     */
    private function getDefaultFunctions($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
		$ret = <<<EOT
// keywords
{$moduleDirname}MetaKeywords(\${$moduleDirname}->getConfig('keywords'));
// description
{$moduleDirname}MetaDescription({$language}DESC);
//
\$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', {$stuModuleDirname}_URL.'/index.php');\n
EOT;

		return $ret;
	}
	
	/**
     * @private function getIncludeFooterFile
     *     
     * @param null
	 * @return string
     */
    private function getIncludeFooterFile()
    {
        $ret = <<<EOT
include  __DIR__ . '/footer.php';
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
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content 	  .= $this->getIncludeHeaderFile();
		$content 	  .= $this->getTemplateHeaderFile($moduleDirname);
        $content 	  .= $this->getBodyIndex($moduleDirname, $table, $language);
		$content 	  .= $this->getDefaultFunctions($moduleDirname, $language);
		$content 	  .= $this->getIncludeFooterFile();
		//
		$this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

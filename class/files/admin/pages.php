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
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
require_once 'objects.php';
class AdminPages extends TDMCreateFile
{
	/*
	* @var string
	*/
	private $adminobjects = null;		
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() {  
		parent::__construct();
		$this->adminobjects = AdminObjects::getInstance();
		$this->tdmcfile = TDMCreateFile::getInstance();        
	}	
	/*
	*  @static function &getInstance
	*  @param null
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
	*/
	public function write($module, $table) {    
		$this->setModule($module);
		$this->setTable($table);
	}
	/*
	*  @public function getAdminPagesHeader
	*  @param string $moduleDirname
	*  @param string $tableName
	*/
	public function getAdminPagesHeader($moduleDirname, $table, $fpif) {  
		
		$ret = <<<EOT
include_once 'header.php';
//It recovered the value of argument op in URL$
\$op = XoopsRequest::getString('op', 'list');
// Request {$fpif}
\${$fpif} = XoopsRequest::getInt('{$fpif}');
// Switch options
switch (\$op) 
{\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesList
	*  @param string $moduleDirname
	*  @param string $table
	*  @param string $tableFieldname	
	*  @param string $language
	*  @param string $fields
	*  @param string $fpif
	*  @param string $fpmf
	*/
	public function getAdminPagesList($moduleDirname, $table, $tableFieldname, $language, $fields, $fpif, $fpmf) {  
		$stuModuleDirname = strtoupper($moduleDirname);
		$tableName = $table->getVar('table_name');
		$tableAutoincrement = $table->getVar('table_autoincrement');
		$stuTableName = strtoupper($tableName);
		$stuTableFieldname = strtoupper($tableFieldname);
		$ret = <<<EOT
    case 'list': 
    default:  
		\$start = XoopsRequest::getInt('start', 0);
		\$limit = XoopsRequest::getInt('limit', \${$moduleDirname}->getConfig('adminpager'));		
		\$template_main = '{$moduleDirname}_admin_{$tableName}.tpl';
		\$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
		\$adminMenu->addItemButton({$language}ADD_{$stuTableFieldname}, '{$tableName}.php?op=new', 'add');		
		\$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());
		\$criteria = new CriteriaCompo();
		\$criteria->setSort('{$fpif} ASC, {$fpmf}');
		\$criteria->setOrder('ASC');
		\${$tableName}_rows = \${$tableName}Handler->getCount(\$criteria);
		\${$tableName}_arr = \${$tableName}Handler->getAll(\$criteria);
		unset(\$criteria);
		\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_url', {$stuModuleDirname}_URL);
		\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
		// Table view
		if (\${$tableName}_rows > 0) 
		{						
			foreach (array_keys(\${$tableName}_arr) as \$i)
			{\n
EOT;
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			$fieldParent = $fields[$f]->getVar('field_parent');
			// Verify if table_fieldname is not empty
			$lpFieldName = !empty($tableFieldname) ? substr($fieldName, 0, strpos($fieldName, '_')) : $tableName;				
			$rpFieldName = $this->tdmcfile->getRightString($fieldName);
			//				
			$fieldElement = $fields[$f]->getVar('field_element');				
			if( ($fields[$f]->getVar('field_admin') == 1) || ($tableAutoincrement == 1) ) {	
				switch($fieldElement) {
					case 3:
					case 4:
						$ret .= $this->adminobjects->getTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName);
					break;
					case 8:
						$ret .= $this->adminobjects->getSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName);
					break;
					case 11:
						$ret .= $this->adminobjects->getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName);
					break;
					case 13:
						$ret .= $this->adminobjects->getTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName);
					break;
					default:
						if( ($fieldParent == 1) && !$table->getVar('table_category') ) {
							if($fieldElement > 13) {					
								$fieldElements = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
								$fieldElementTid = $fieldElements->getVar('fieldelement_tid');
								$fieldElementName = $fieldElements->getVar('fieldelement_name');
								$rpFieldElementName = strtolower(str_replace('Table : ', '', $fieldElementName));
							}
							//
							$fieldNameParent = $fieldName;
							//
							$criteriaFieldsTopic = new CriteriaCompo();
							$criteriaFieldsTopic->add(new Criteria('field_tid', $fieldElementTid));
							$fieldsTopic = $this->tdmcreate->getHandler('fields')->getObjects($criteriaFieldsTopic);
							unset($criteriaFieldsTopic);
							foreach(array_keys($fieldsTopic) as $ft) 
							{
								if( $fieldsTopic[$ft]->getVar('field_main') == 1 ) {
									$fieldNameTopic = $fieldsTopic[$ft]->getVar('field_name');
								}
							}
							$ret .= $this->adminobjects->getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $rpFieldElementName, $fieldNameParent, $fieldNameTopic);
						} else {
							$ret .= $this->adminobjects->getSimpleGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName);
						}
					break;
				}				
			}
		}
			$ret .= <<<EOT
				\$GLOBALS['xoopsTpl']->append('{$tableName}_list', \${$lpFieldName});
                unset(\${$lpFieldName});
			}\n
EOT;
			$ret .= <<<EOT
			if ( \${$tableName}_rows > \$limit ) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				\$pagenav = new XoopsPageNav(\${$tableName}_rows, \$limit, \$start, 'start', 'op=list&limit=' . \$limit);
				\$GLOBALS['xoopsTpl']->assign('pagenav', \$pagenav->renderNav(4));
			}
        } else {
			\$GLOBALS['xoopsTpl']->assign('error', {$language}THEREARENT_{$stuTableName});
		}	
    break;\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesNew
	*  @param string $moduleDirname
	*  @param string $tableName	
	*  @param string $language
	*/
	public function getAdminPagesNew($moduleDirname, $tableName, $language) {  
		$stuTableName = strtoupper($tableName);
		$ret = <<<EOT
	case 'new':		
		\$template_main = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
		\$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());	
		// Get Form		
        \${$tableName}Obj =& \${$tableName}Handler->create();
        \$form = \${$tableName}Obj->getForm();
		\$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesSave
	*  @param string $moduleDirname
	*  @param string $tableName
	*  @param string $language	
	*  @param string $fields
	*  @param string $fpif
	*  @param string $fpmf
	*/
	public function getAdminPagesSave($moduleDirname, $tableName, $language, $fields, $fpif, $fpmf) 
	{  				
		$ret = <<<EOT
	case 'save':
		if ( !\$GLOBALS['xoopsSecurity']->check() ) {
           redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset(\${$fpif})) {
           \${$tableName}Obj =& \${$tableName}Handler->get(\${$fpif});
        } else {
           \${$tableName}Obj =& \${$tableName}Handler->create();
        }
		// Set Vars\n
EOT;
		foreach (array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			$fieldElement = $fields[$f]->getVar('field_element');
			if($f > 0) { // If we want to hide field id
				switch($fieldElement) {
					case 5:
					case 6:
						$ret .= $this->adminobjects->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
					break;
					case 10:
						$ret .= $this->adminobjects->getImageListSetVar($moduleDirname, $tableName, $fieldName);
					break;
					case 11:
						$ret .= $this->adminobjects->getUploadImageSetVar($moduleDirname, $tableName, $fieldName);
					break;
					case 12:
						$ret .= $this->adminobjects->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
					break;
					case 13:
						$ret .= $this->adminobjects->getTextDateSelectSetVar($tableName, $fieldName);
					break;
					default:
						$ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
					break;
				}
			}
		}

		$ret .= <<<EOT
		// Insert Data
		if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
           redirect_header('{$tableName}.php?op=list', 2, {$language}FORMOK);
        }
		// Get Form
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
        \$form =& \${$tableName}Obj->getForm();
		\$GLOBALS['xoopsTpl']->assign('form', \$form->render());
	break;\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesEdit
	*  @param string $moduleDirname
	*  @param string $tableName
	*  @param string $tableFieldname	
	*  @param string $language
	*  @param string $fpif
	*/
	public function getAdminPagesEdit($moduleDirname, $tableName, $tableFieldname, $language, $fpif) {  
		$stuTableName = strtoupper($tableName);
		$stuTableFieldname = strtoupper($tableFieldname);
		$ret = <<<EOT
	case 'edit':	    
		\$template_main = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}ADD_{$stuTableFieldname}, '{$tableName}.php?op=new', 'add');
		\$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
		\$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());	
		// Get Form
		\${$tableName}Obj = \${$tableName}Handler->get(\${$fpif});
		\$form = \${$tableName}Obj->getForm();
		\$GLOBALS['xoopsTpl']->assign('form', \$form->render());
	break;\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesDelete
	*  @param string $tableName	
	*  @param string $language
	*/
	public function getAdminPagesDelete($tableName, $language, $fpif, $fpmf) {  
		
		$ret = <<<EOT
	case 'delete':
		\${$tableName}Obj =& \${$tableName}Handler->get(\${$fpif});
		if (isset(\$_REQUEST['ok']) && \$_REQUEST['ok'] == 1) {
			if ( !\$GLOBALS['xoopsSecurity']->check() ) {
				redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
			}
			if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
				redirect_header('{$tableName}.php', 3, {$language}FORMDELOK);
			} else {
				echo \${$tableName}Obj->getHtmlErrors();
			}
		} else {
			xoops_confirm(array('ok' => 1, '{$fpif}' => \${$fpif}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORMSUREDEL, \${$tableName}Obj->getVar('{$fpmf}')));
		}
	break;\n
EOT;
		return $ret;
	}
	
	/*
	*  @public function getAdminPagesFooter
	*  @param null
	*/
	public function getAdminPagesFooter() { 		
		$ret = <<<EOT
}
include_once 'footer.php';
EOT;
		return $ret;
	}
	
	/*
	*  @public function render
	*  @param null
	*/
	public function renderFile($filename) 
	{    
        $module = $this->getModule();
		$table = $this->getTable();
		$moduleDirname = $module->getVar('mod_dirname');      
		$tableName = $table->getVar('table_name');	
		$tableFieldname = $table->getVar('table_fieldname');		
		$language = $this->getLanguage($moduleDirname, 'AM');
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			if($f == 0) {
				$fpif = $fieldName;
			}
			if($fields[$f]->getVar('field_main') == 1) {
				$fpmf = $fieldName;
			}
		}		
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .=	$this->getAdminPagesHeader($moduleDirname, $tableName, $fpif);
		$content .=	$this->getAdminPagesList($moduleDirname, $table, $tableFieldname, $language, $fields, $fpif, $fpmf);
		$content .=	$this->getAdminPagesNew($moduleDirname, $tableName, $language);		
		$content .=	$this->getAdminPagesSave($moduleDirname, $tableName, $language, $fields, $fpif, $fpmf);
		$content .=	$this->getAdminPagesEdit($moduleDirname, $tableName, $tableFieldname, $language, $fpif);
		$content .=	$this->getAdminPagesDelete($tableName, $language, $fpif, $fpmf);
		$content .= $this->getAdminPagesFooter();
		//
		$this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}
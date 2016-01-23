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
 * @version         $Id: AdminXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');
/**
 * Class AdminXoopsCode.
 */
class AdminXoopsCode
{    	
	/*
    * @var string
    */
    private $xoopscode;
	
	/*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->phpcode = TDMCreatePhpCode::getInstance();
		$this->xoopscode = TDMCreateXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminXoopsCode
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
     *  @public function getAdminTemplateMain
     *  @param $moduleDirname
     *  @param $tableName
	 *
     *  @return string
     */
    public function getAdminTemplateMain($moduleDirname, $tableName)
    {
        return "\$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n";
    }

    /*
     *  @public function getAdminTemplateMain
     *  @param $language
     *  @param $tableName
	 *  @param $stuTableSoleName
     *  @param $type
	 *
     *  @return string
     */
    public function getAdminItemButton($language, $tableName, $stuTableSoleName, $op = '?op=new', $type = 'add')
    {
        $stuType = strtoupper($type);
		$adminMenu = '$adminMenu->addItemButton(';
		if($type === 'add') {
			$ret = $adminMenu."{$language}ADD_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
		} else {
			$ret = $adminMenu."{$language}{$stuTableSoleName}_{$stuType}, '{$tableName}.php{$op}', '{$type}');\n";
		}

        return $ret;
    }

    /**
     *  @public function getAdminAddNavigation
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminAddNavigation($tableName)
    {        
        return "\$adminMenu->addNavigation('{$tableName}.php')";
    }

    /**
     *  @public function getAdminObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getAdminObjHandlerCreate($tableName)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->create();\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeAddInfoBox
    *  @param $language
    *  
    *  @return string
    */
    public function getXoopsCodeAddInfoBox($language)
    {
        return "\$adminMenu->addInfoBox({$language});\n";
    }

    /*
    *  @public function getXoopsCodeAddInfoBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getXoopsCodeAddInfoBoxLine($language, $label = '', $var = '')
    {
        $aMenu = '$adminMenu->addInfoBoxLine(';
		if ($var != '') {
            $ret = $aMenu."{$language}, '<label>'.{$label}.'</label>', {$var});\n";
        } else {
            $ret = $aMenu."{$language}, '<label>'.{$label}.'</label>');\n";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeAddConfigBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getXoopsCodeAddConfigBoxLine($language, $label = '', $var = '')
    {
        $aMenu = '$adminMenu->addConfigBoxLine(';
		if ($var != '') {
            $ret = $aMenu."{$language}, '{$label}', {$var});\n";
        } else {
            $ret = $aMenu."{$language}, '{$label}');\n";
        }

        return $ret;
    }    
    
    /*
    *  @public function getXoopsCodeImageListSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->xoopscode->getXoopsCodeMediaUploader('uploader', "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'", $tableName, $moduleDirname);
        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        $ifelse = "//\$uploader->setPrefix('{$fieldName}_');\n";
        $ifelse .= "//\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])\n";
        $contentElseInt = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->phpcode->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        $ret .= $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUploadImageSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->phpcode->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->getXoopsCodeMediaUploader('uploader', "{$stuModuleDirname}_UPLOAD_IMAGE_PATH", $tableName, $moduleDirname);

        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        $ifelse = "\$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , \$_FILES['attachedfile']['name']);\n";
        $ifelse .= "\$imgName = str_replace(' ', '', \$_POST['{$fieldMain}']).'.'.\$extension;\n";
        $ifelse .= "\$uploader->setPrefix(\$imgName);\n";
        $ifelse .= "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);\n";
        $contentElseInt = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->phpcode->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        $ret .= $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);

        return $ret;
    }

    /*
    *  @public function getXoopsCodeFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @param $formatUrl
    *  @return string
    */
    public function getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName, $formatUrl = false)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        if ($formatUrl) {
            $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])");
            $ret .= $this->phpcode->getPhpCodeCommentLine('Set Var', $fieldName);
        } else {
            $ret = $this->phpcode->getPhpCodeCommentLine('Set Var', $fieldName);
        }
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->getXoopsCodeMediaUploader('uploader', "{$stuModuleDirname}_UPLOAD_FILES_PATH", $tableName, $moduleDirname);
        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        if ($formatUrl) {
            $ifelse = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])\n";
        } else {
            $ifelse = "//\$uploader->setPrefix('{$fieldName}_');\n";
            $ifelse .= "//\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])\n";
        }
        $contentElse = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->phpcode->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElse);

        $ret .= $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse);

        return $ret;
    }

    /**
     *  @public function getAdminXoopsCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getAdminXoopsCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine($comment = 'Set Vars', $var = '');
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->xoopscode->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->xoopscode->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->xoopscode->getXoopsCodeUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->xoopscode->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->xoopscode->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->xoopscode->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->xoopscode->getXoopsCodeSimpleSetVar($tableName, $fieldName);
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getAdminXoopsCodeXoopsSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminXoopsCodeXoopsSecurity($tableName)
    {
        $ret = <<<EOT
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getAdminXoopsCodeInsertData($tableName, $language)
    {
        $ret = <<<EOT
        // Insert Data
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
			redirect_header('{$tableName}.php?op=list', 2, {$language}FORM_OK);
        }\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getAdminXoopsCodeGetFormError($tableName)
    {
        $ret = <<<EOT
        // Get Form
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
        \$form =& \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getAdminXoopsCodeGetFormId($tableName, $fieldId)
    {
        $ret = <<<EOT
        // Get Form
        \${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});
        \$form = \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getAdminXoopsCodeGetObjHandlerId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getAdminXoopsCodeGetObjHandlerId($tableName, $fieldId)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminXoopsCodeDelete($tableName, $language, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
    case 'delete':
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORMDELOK);
            } else {
                echo \${$tableName}Obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORMSUREDEL, \${$tableName}Obj->getVar('{$fieldMain}')));
        }
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeUpdate
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminXoopsCodeUpdate($moduleDirname, $tableName, $language, $fieldId, $fieldMain)
    {
        $upModuleName = strtoupper($moduleDirname);
        $ret = <<<EOT
    case 'update':
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar("\${$tableName}_display", \$_POST["\${$tableName}_display"]);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, _AM_{$upModuleName}_FORMOK);
        }
        echo \${$tableName}Obj->getHtmlErrors();
    break;\n
EOT;

        return $ret;
    }
}

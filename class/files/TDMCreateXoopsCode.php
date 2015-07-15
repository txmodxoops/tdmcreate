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
 * @version         $Id: TDMCreateXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreateXoopsCode.
 */
class TDMCreateXoopsCode extends TDMCreatePhpCode
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
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateXoopsCode
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
    *  @public function getXoopsCodeSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @param string $var
    *  @return string
    */
    public function getXoopsCodeSetVar($tableName, $fieldName, $var)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', {$var});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeTextDateSelectSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectSetVar($tableName, $fieldName)
    {
        $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "strtotime(\$_POST['{$fieldName}'])");

        return $ret;
    }

    /*
    *  @public function getXoopsCodeCheckBoxOrRadioYNSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')");

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUrlFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUrlFileSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])");
        $ret .= $this->getXoopsCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->getXoopsCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= <<<EOT
        \$uploader = new XoopsMediaUploader({$stuModuleDirname}_UPLOAD_FILES_PATH . '/{$tableName}',
														\${$moduleDirname}->getConfig('mimetypes'),
                                                        \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])) {
            \$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        }\n
EOT;

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
        $ret = $this->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= <<<EOT
        \$uploaddir = XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32';
        \$uploader = new XoopsMediaUploader(\$uploaddir, \${$moduleDirname}->getConfig('mimetypes'),
                                                         \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])) {
            //\$uploader->setPrefix('{$fieldName}_');
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        } else {
            \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);
        }\n
EOT;

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
        $ret = $this->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= <<<EOT
        \$uploader = new XoopsMediaUploader({$stuModuleDirname}_UPLOAD_IMAGE_PATH.'/{$tableName}',
														\${$moduleDirname}->getConfig('mimetypes'),
                                                        \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])) {
			\$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , \$_FILES['attachedfile']['name']);
            \$imgName = str_replace(' ', '', \$_POST['{$fieldMain}']).'.'.\$extension;
			\$uploader->setPrefix(\$imgName);
            \$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        } else {
            \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUploadFileSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploader = new XoopsMediaUploader({$stuModuleDirname}_UPLOAD_FILES_PATH.'/{$tableName}',
														\${$moduleDirname}->getConfig('mimetypes'),
                                                        \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])) {
            //\$uploader->setPrefix('{$fieldName}_') ;
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeIdGetVar
    *  @param string $lpFieldName
    *  @return string
    */
    public function getXoopsCodeIdGetVar($lpFieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var Id
\t\t\t\t\${$lpFieldName}['id'] = \$i;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeSimpleGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeSimpleGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeTopicGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @param string $fieldNameTopic
    *  @return string
    */
    public function getXoopsCodeTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeParentTopicGetVar
    *  @param string $moduleDirname
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableSoleNameTopic
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @return string
    */
    public function getXoopsCodeParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent)
    {
        $ret = <<<EOT
\t\t\t\tif(!isset(\${$tableNameTopic}Handler)) {
\t\t\t\t\t// Get {$tableNameTopic} Handler
\t\t\t\t\t\${$tableNameTopic}Handler =& \${$moduleDirname}->getHandler('{$tableNameTopic}');
\t\t\t\t}
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableNameTopic}Handler->get{$tableSoleNameTopic}FromId(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUploadImageGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$fieldName} ? \${$fieldName} : 'blank.gif';\n
EOT;

        return $ret;
    }
    /*
    *  @public function getXoopsCodeUrlFileGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }
    /*
    *  @public function getXoopsCodeTextAreaGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeSelectUserGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    * @return string
    */
    public function getXoopsCodeSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeTextDateSelectGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName)
    {
        $ret = "\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeUserHeader($moduleDirname, $tableName)
    {
        $ret = $this->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $this->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /*
    *  @public function getXoopsCodePermissionsHeader
    *  @param null
    */
    /**
     * @return string
     */
    public function getXoopsCodePermissionsHeader()
    {
        $ret = <<<EOT
// Permission
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
\$gperm_handler =& xoops_gethandler('groupperm');
if (is_object(\$xoopsUser)) {
    \$groups = \$xoopsUser->getGroups();
} else {
    \$groups = XOOPS_GROUP_ANONYMOUS;
}
EOT;

        return $ret;
    }

    /**
     *  @public function getXoopsCodeGetFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldId($fields)
    {
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXoopsCodeGetFieldParentId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldParentId($fields)
    {
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }

        return $fieldPid;
    }

    /**
     *  @public function getXoopsCodeUserSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, '$'.$fieldName);
            }
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeXoopsRequest
    *  @param $left
    *  @param $var1
    *  @param $var2
    *  @param $type
    *  @param $metod
    *  @return string
    */
    public function getXoopsCodeXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false)
    {
        if ($type == 'String') {
            $ret = "\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ($type == 'Int') {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}', {$var2});\n";
        } elseif ($type == 'Int' && $metod !== false) {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}', {$var2}, '{$metod}');\n";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeTemplateMain
    *  @param $moduleDirname
    *  @param $filename
    *  @return string
    */
    public function getXoopsCodeTemplateMain($moduleDirname, $filename)
    {
        $ret = "\$templateMain = '{$moduleDirname}_admin_{$filename}.tpl';\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeXoopsTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAssign($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->assign('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppend
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppend($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->append('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppendByRef
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppendByRef($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /*
    *  @public function getXoopsCodeItemButton
    *  @param $language
    *  @param $tableName
    *  @param $admin
    *  @return string
    */
    public function getXoopsCodeItemButton($language, $tableName, $tableSoleName, $op = '?op=new', $type = 'add')
    {
        $stuTableName = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $stuType = strtoupper($type);
        if ($type = 'add') {
            $ret = "\$adminMenu->addItemButton({$language}{$stuType}_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
        } elseif ($type = 'list') {
            $ret = "\$adminMenu->addItemButton({$language}{$stuTableName}_{$stuType}, '{$tableName}.php', '{$type}');\n";
        } else {
            $ret = "\$adminMenu->addItemButton({$language}{$stuTableName}_{$stuType}, '{$tableName}.php', '{$type}');\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerCreate($tableName)
    {
        $ret = "\${$tableName}Obj =& \${$tableName}Handler->create();\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = $this->getPhpCodeCommentLine('Set Vars');

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->getImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->getUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->getTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getSimpleSetVar($tableName, $fieldName);
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeXoopsSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeXoopsSecurity($tableName)
    {
        $ret = <<<EOT
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getXoopsCodeInsertData($tableName, $language)
    {
        $ret = <<<EOT
        // Insert Data
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
			redirect_header('{$tableName}.php?op=list', 2, {$language}FORM_OK);
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeRedirectHeader
    *  @param $tableName
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @return string
    */
    public function getXoopsCodeRedirectHeader($tableName, $options, $numb = 2, $var)
    {
        return "redirect_header('{$tableName}.php{$options}', {$numb}, {$var});\n";
    }

    /*
    *  @public function getXoopsCodeXoopsSecurityCheck
    *  @param null
    *  @return boolean
    */
    public function getXoopsCodeXoopsSecurityCheck()
    {
        return "\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getXoopsCodeXoopsSecurityGetError
    *  @param null
    *  @return string
    */
    public function getXoopsCodeXoopsSecurityGetError()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     *  @public function getXoopsCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeGetFormError($tableName)
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
     *  @public function getXoopsCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getXoopsCodeGetFormId($tableName, $fieldId)
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
     *  @public function getXoopsCodeHandler
     *
     *  @param string $tableName
     *  @param string $var
     *
     *  @return string
     */
    public function getXoopsCodeHandler($tableName, $var, $get = false, $insert = false, $delete = false, $obj = '')
    {
        if ($get) {
            $ret = "\${$tableName}Handler->get(\${$var});";
        } elseif ($insert && ($obj != '')) {
            $ret = "\${$tableName}Handler->insert(\${$var}{$obj});";
        } elseif ($delete && ($obj != '')) {
            $ret = "\${$tableName}Handler->delete(\${$var}{$obj});";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeCaseDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getXoopsCodeCaseDelete($language, $tableName, $fieldId, $fieldMain)
    {
        $content = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORM_DELETE_OK);
            } else {
                echo \${$tableName}Obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORM_SURE_DELETE, \${$tableName}Obj->getVar('{$fieldMain}')));
        }\n
EOT;

        return $this->getPhpCodeCaseSwitch('delete', $content);
    }

    /*
    *  @public function getXoopsCodeUpdate
    *  @param $language
    *  @param $tableName
    *  @param $fieldId
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUpdate($language, $tableName, $fieldId, $fieldName)
    {
        $content = <<<EOT
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, {$language}FORM_OK);
        }
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
EOT;

        return $this->getPhpCodeCaseSwitch('update', $content);
    }
}

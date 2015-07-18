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
class TDMCreateXoopsCode
{
    /*
    * @var mixed
    */
    private $phpcode = null;
	
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
        return "\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /*
    *  @public function getXoopsCodeTextDateSelectSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectSetVar($tableName, $fieldName)
    {
        return $this->getXoopsCodeSetVar($tableName, $fieldName, "strtotime(\$_POST['{$fieldName}'])");
    }

    /*
    *  @public function getXoopsCodeCheckBoxOrRadioYNSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        $ret = $this->getXoopsCodeSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')");

        return $ret;
    }

    /*
    *  @public function getXoopsCodeGetConfig
    *  @param $moduleDirname
    *  @param $name
    *  @return string
    */
    public function getXoopsCodeGetConfig($moduleDirname, $name)
    {
        return "\${$moduleDirname}->getConfig('{$name}')";
    }

    /*
    *  @public function getXoopsCodeGetConfig
    *  @param $var
    *  @param $dirPath
    *  @param $tableName
    *  @param $moduleDirname
    *  @return string
    */
    public function getXoopsCodeMediaUploader($var = '', $dirPath, $tableName, $moduleDirname)
    {
        return "\${$var} = new XoopsMediaUploader({$dirPath} . '/{$tableName}',
														\${$moduleDirname}->getConfig('mimetypes'),
                                                        \${$moduleDirname}->getConfig('maxsize'), null, null);\n";
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
        $ret = $this->phpcode->phpcode->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->getXoopsCodeMediaUploader('uploader', "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'", $tableName, $moduleDirname);
        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        $ifelse = "//\$uploader->setPrefix('{$fieldName}_');\n";
        $ifelse .= "//\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])\n";
        $contentElseInt = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->phpcode->getPhpCodeConditions("!\$uploader->upload()", '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        return $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);
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
        $ifelse .= $this->phpcode->getPhpCodeConditions("!\$uploader->upload()", '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        return $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);
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
        $ifelse .= $this->phpcode->getPhpCodeConditions("!\$uploader->upload()", '', '', $contentIf, $contentElse);

        return $this->phpcode->getPhpCodeConditions($fetchMedia, '', '', $ifelse);
    }

    /*
    *  @public function getXoopsCodeIdGetVar
    *  @param string $lpFieldName
    *  @return string
    */
    public function getXoopsCodeIdGetVar($lpFieldName)
    {
        return "\${$lpFieldName}['id'] = \$i;\n";
    }

    /*
    *  @public function getXoopsCodeGetVarAll
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
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
        return $this->getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
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
        return "\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n";
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
        return "\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
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
        return "\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName)
    {
        return "\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getXoopsCodeUserHeader($moduleDirname, $tableName)
    {
        $ret = $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

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
        $fieldId = 'id';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXoopsCodeGetFieldName
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldName($fields)
    {
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
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
        $fieldPid = 'pid';
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
                $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
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
        return "\$templateMain = '{$moduleDirname}_admin_{$filename}.tpl';\n";
    }

    /**
     *  @public function getXoopsCodeTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeTplAssign($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->assign('{$tplString}', {$phpRender});\n";
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
        return "\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
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
        return "\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
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
        $ret = '';

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName, true);
                        break;
                    case 13:
                        $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeSecurity($tableName)
    {
        $securityError = $this->getXoopsCodeSecurityGetError();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $content = $this->getXoopsCodeRedirectHeader($tableName, '', 3, $implode);
        $securityCheck = $this->getXoopsCodeSecurityCheck();

        return $this->phpcode->getPhpCodeConditions('!'.$securityCheck, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getXoopsCodeInsertData($tableName, $language)
    {
        $content = $this->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = $this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');

        return $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeRedirectHeader
    *  @param $tableName
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @return string
    */
    public function getXoopsCodeRedirectHeader($tableName, $options = '', $numb = 2, $var)
    {
        return "redirect_header('{$tableName}.php{$options}', {$numb}, {$var});\n";
    }

    /*
    *  @public function getXoopsCodeSecurityCheck
    *  @param null
    *  @return boolean
    */
    public function getXoopsCodeSecurityCheck()
    {
        return "\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getXoopsCodeSecurityGetError
    *  @param null
    *  @return string
    */
    public function getXoopsCodeSecurityGetError()
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
        /*$isset = $this->phpcode->getPhpCodeIsset($fieldId);
        $if1 = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $get = $this->getXoopsCodeHandler($tableName, $fieldId, true);
        $if2 = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $content = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        //$content .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->getXoopsCodeRedirectHeader($tableName, '', 2, "{$language}FORM_DELETE_OK");

        $else = $this->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");

        $content .= $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $redirect, $else);*/

        return $this->phpcode->getPhpCodeCaseSwitch('delete', $content);
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
        $isset = $this->phpcode->getPhpCodeIsset($fieldId);
        $get = $this->getXoopsCodeHandler($tableName, $fieldId, true);
        $content = $this->phpcode->getPhpCodeConditions($isset, '', '', "\${$tableName}Obj =& ".$get);
        $content .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_UPDATE_OK");
        $content .= $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $redirect);

        $content .= $this->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");

        return $this->phpcode->getPhpCodeCaseSwitch('update', $content);
    }
}

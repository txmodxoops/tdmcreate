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
 * @version         $Id: TDMCreatePhpCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreatePhpCode.
 */
class TDMCreatePhpCode extends TDMCreateFile
{
    /*
    * @var string
    */
    protected $phpcode = null;

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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreatePhpCode
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
    *  @public function getPhpCodeCommentLine
    *  @param $var
    *  @return string
    */
    public function getPhpCodeCommentLine($comment, $var = '')
    {
        $ret = "// {$comment} {$var}\n";

        return $ret;
    }

    /*
    *  @public function getPhpCodeIncludeDir
    *  @param $filename
    *  @return string
    */
    public function getPhpCodeIncludeDir($filename = '')
    {
        $ret = "include __DIR__ .'/{$filename}.php';\n";

        return $ret;
    }
	
	/*
    *  @public function getSimpleSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getSimpleSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n
EOT;

        return $ret;
    }

    /*
    *  @public function getTextDateSelectSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getTextDateSelectSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', strtotime(\$_POST['{$fieldName}']));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getCheckBoxOrRadioYNSetVar
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', ((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUrlFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getUrlFileSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', formatUrl(\$_REQUEST['{$fieldName}']));\n
		// Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
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
    *  @public function getImageListSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getImageListSetVar($moduleDirname, $tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
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
    *  @public function getUploadImageSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
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
    *  @public function getUploadFileSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getUploadFileSetVar($moduleDirname, $tableName, $fieldName)
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
    *  @public function getIdGetVar
    *  @param string $lpFieldName
    *  @return string
    */
    public function getIdGetVar($lpFieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var Id
\t\t\t\t\${$lpFieldName}['id'] = \$i;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getSimpleGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getSimpleGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getTopicGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @param string $fieldNameTopic
    *  @return string
    */
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getParentTopicGetVar
    *  @param string $moduleDirname
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $tableSoleNameTopic
    *  @param string $tableNameTopic
    *  @param string $fieldNameParent
    *  @return string
    */
    public function getParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent)
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
    *  @public function getUploadImageGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$fieldName} ? \${$fieldName} : 'blank.gif';\n
EOT;

        return $ret;
    }
    /*
    *  @public function getUrlFileGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }
    /*
    *  @public function getTextAreaGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getSelectUserGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    * @return string
    */
    public function getSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getTextDateSelectGetVar
    *  @param string $lpFieldName
    *  @param string $rpFieldName
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }
	
	/*
    *  @public function getUserHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @return string
    */
    public function getUserHeader($moduleDirname, $tableName)
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUserIndex
    *  @param string $moduleDirname
    *  @return string
    */
    public function getUserIndex($moduleDirname)
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_index.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUserFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserFooter()
    {
        $ret = <<<EOT
include  __DIR__ . '/footer.php';
EOT;

        return $ret;
    }    
    
    /**
     *  @public function getUserSaveElements
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getUserSaveFieldId($fields)
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
     *  @public function getUserSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getSimpleSetVar($tableName, $fieldName);
            }
        }

        return $ret;
    }    
    
    /*
     * @public function getPhpCodeConditions
     * @param string $condition
     * @param string $operator
     * @param string $type
     * @param string $contentIf
     * @param mixed  $contentElse
     *
     * @return string
     */
    public function getPhpCodeConditions($condition = '', $operator = '', $type = '', $contentIf = '', $contentElse = false)
    {
        if (false !== $contentElse) {
            $ret = <<<EOT
	if ({$condition}{$operator}{$type}) {
		{$contentIf}
	}\n
EOT;
        } else {
            $ret = <<<EOT
	if ({$condition}{$operator}{$type}) {
		{$contentIf}
	} else {
		{$contentElse}
    }\n
EOT;
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeXoopsRequest
    *  @param $left
	*  @param $var1
    *  @param $var2
    *  @param $type
    *  @param $metod
    *  @return string
    */
    public function getPhpCodeXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false)
    {
        if ($type == 'String') {
            $ret = "\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ($type == 'Int') {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}');\n";
        } elseif ($type == 'Int' && $metod) {
            $ret = "\${$left} = XoopsRequest::getInt('{$var1}', {$var2}, '{$metod}');\n";
        }

        return $ret;
    }

    /**
     *  @public function getPhpCodeSwitch
     *
     *  @param $op
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeSwitch($op = '', $content = '')
    {
        $ret = <<<EOT
// Switch options
switch (\${$op}){
	{$content}
}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeCaseSwitch
     *
     *  @param $case
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeCaseSwitch($case = 'list', $content, $defaultAfterCase = false, $default = false)
    {
        if (is_string($case)) {
            $ret = "\tcase '{$case}':\n";
        } else {
            $ret = "\tcase {$case}:\n";
        }
        if ($defaultAfterCase) {
            $ret .= <<<EOT
    default:
		{$content}
	break;\n
EOT;
        } else {
            $ret .= <<<EOT
		{$content}
	break;\n
EOT;
        }
        if ($default !== false) {
            $ret = <<<EOT
    default:
		{$default}
	break;\n
EOT;
        }

        return $ret;
    }

    /*
    *  @public function getPhpCodeTemplateMain
    *  @param $moduleDirname
    *  @param $filename
    *  @return string
    */
    public function getPhpCodeTemplateMain($moduleDirname, $filename)
    {
        $ret = "\$templateMain = '{$moduleDirname}_admin_{$filename}.tpl';\n";

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAssign($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->assign('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAppend
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAppend($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->append('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAppendByRef
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAppendByRef($tplString, $phpRender)
    {
        $ret = "\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', \${$phpRender});\n";

        return $ret;
    }

    /*
    *  @public function getPhpCodeItemButton
    *  @param $language
    *  @param $tableName
    *  @param $admin
    *  @return string
    */
    public function getPhpCodeItemButton($language, $tableName, $tableSoleName, $op = '?op=new', $type = 'add')
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
     *  @public function getPhpCodeObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getPhpCodeObjHandlerCreate($tableName)
    {
        $ret = "\${$tableName}Obj =& \${$tableName}Handler->create();\n";

        return $ret;
    }

    /**
     *  @public function getPhpCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getPhpCodeSetVarsObjects($moduleDirname, $tableName, $fields)
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
     *  @public function getPhpCodeXoopsSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getPhpCodeXoopsSecurity($tableName)
    {
        $ret = <<<EOT
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getPhpCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getPhpCodeInsertData($tableName, $language)
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
    *  @public function getPhpCodeRedirectHeader
    *  @param $tableName
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @return string
    */
    public function getPhpCodeRedirectHeader($tableName, $options, $numb = 2, $var)
    {
        return "redirect_header('{$tableName}.php{$options}', {$numb}, {$var});\n";
    }

    /*
    *  @public function getPhpCodeXoopsSecurityCheck
    *  @param null
    *  @return boolean
    */
    public function getPhpCodeXoopsSecurityCheck()
    {
        return "\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getPhpCodeXoopsSecurityGetError
    *  @param null
    *  @return string
    */
    public function getPhpCodeXoopsSecurityGetError()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /*
    *  @public function getPhpCodeImplode
    *  @param $left
    *  @param $right
    *  @return string
    */
    public function getPhpCodeImplode($left, $right)
    {
        return "implode('{$left} ', {$right})";
    }

    /**
     *  @public function getPhpCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getPhpCodeGetFormError($tableName)
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
     *  @public function getPhpCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getPhpCodeGetFormId($tableName, $fieldId)
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
     *  @public function getPhpCodeHandler
     *
     *  @param string $tableName
     *  @param string $var
     *
     *  @return string
     */
    public function getPhpCodeHandler($tableName, $var, $get = false, $insert = false, $delete = false, $obj = '')
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
    *  @public function getPhpCodeCaseDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getPhpCodeCaseDelete($language, $tableName, $fieldId, $fieldMain)
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
    *  @public function getPhpCodeUpdate
    *  @param string $language
    *  @param string $tableName
    *  @param string $fieldId
    *  @return string
    */
    public function getPhpCodeUpdate($language, $tableName, $fieldId)
    {
        $content = <<<EOT
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar("\${$tableName}_display", \$_POST["\${$tableName}_display"]);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, {$language}FORM_OK);
        }
        echo \${$tableName}Obj->getHtmlErrors();\n
EOT;

        return $this->getPhpCodeCaseSwitch('update', $content);
    }
}

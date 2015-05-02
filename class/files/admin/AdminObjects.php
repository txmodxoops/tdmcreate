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
 * @version         $Id: admin_objects.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class AdminObjects
 */
class AdminObjects
{
    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminObjects
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
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getUrlFileSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', formatUrl(\$_REQUEST['{$fieldName}']));\n
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
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            //\$uploader->setPrefix('{$fieldName}_');
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
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
    public function getUploadImageSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret              = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = {$stuModuleDirname}_UPLOAD_PATH.'/images/{$tableName}';
        \$uploader = new XoopsMediaUploader(\$uploaddir, \${$moduleDirname}->getConfig('mimetypes'),
                                                         \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            //\$uploader->setPrefix('{$fieldName}_');
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
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
        $ret              = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = {$stuModuleDirname}_UPLOAD_PATH.'/files/{$tableName}';
        \$uploader = new XoopsMediaUploader(\$uploaddir, \${$moduleDirname}->getConfig('mimetypes'),
                                                         \${$moduleDirname}->getConfig('maxsize'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            //\$uploader->setPrefix('{$fieldName}_') ;
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
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
\t\t\t\t\$upload_image = \${$fieldName} ? \${$fieldName} : 'blank.gif';
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \$upload_image;\n
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
}
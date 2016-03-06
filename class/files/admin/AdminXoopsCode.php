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

/**
 * Class AdminXoopsCode.
 */
class AdminXoopsCode
{
    /*
    * @var mixed
    */
    private $tf = null;

    /*
    * @var mixed
    */
    private $pc = null;

    /*
    * @var mixed
    */
    private $xc = null;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tf = TDMCreateFile::getInstance();
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
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
    public function getAdminTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n";
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
    public function getAdminItemButton($language, $tableName, $stuTableSoleName, $op = '?op=new', $type = 'add', $t = '')
    {
        $stuType = strtoupper($type);
        $adminMenu = $t.'$adminMenu->addItemButton(';
        if ($type === 'add') {
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
    public function getAdminAddNavigation($tableName, $t = '')
    {
        return "{$t}\$adminMenu->addNavigation('{$tableName}.php')";
    }

    /*
    *  @public function getAdminXoopsCodeAddInfoBox
    *  @param $language
    *  
    *  @return string
    */
    public function getAdminXoopsCodeAddInfoBox($language, $t = '')
    {
        return "{$t}\$adminMenu->addInfoBox({$language});\n";
    }

    /*
    *  @public function getAdminXoopsCodeAddInfoBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getAdminXoopsCodeAddInfoBoxLine($language, $label = '', $var = '', $t = '')
    {
        $aMenu = $t.'$adminMenu->addInfoBoxLine(';
        if ($var != '') {
            $ret = $aMenu."{$language}, '<label>'.{$label}.'</label>', {$var});\n";
        } else {
            $ret = $aMenu."{$language}, '<label>'.{$label}.'</label>');\n";
        }

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeAddConfigBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getAdminXoopsCodeAddConfigBoxLine($language, $label = '', $var = '', $t = '')
    {
        $aMenu = $t.'$adminMenu->addConfigBoxLine(';
        if ($var != '') {
            $ret = $aMenu."{$language}, '{$label}', {$var});\n";
        } else {
            $ret = $aMenu."{$language}, '{$label}');\n";
        }

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeImageListSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getAdminXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName)
    {
        $ret = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->xc->getAdminXoopsCodeMediaUploader('uploader', "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'", $tableName, $moduleDirname);
        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        $ifelse = "//\$uploader->setPrefix('{$fieldName}_');\n";
        $ifelse .= "//\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])\n";
        $contentElseInt = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeUploadImageSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getAdminXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName);
        $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->getAdminXoopsCodeMediaUploader('uploader', "{$stuModuleDirname}_UPLOAD_IMAGE_PATH", $tableName, $moduleDirname);

        $fetchMedia = "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])";
        $ifelse = "\$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , \$_FILES['attachedfile']['name']);\n";
        $ifelse .= "\$imgName = str_replace(' ', '', \$_POST['{$fieldMain}']).'.'.\$extension;\n";
        $ifelse .= "\$uploader->setPrefix(\$imgName);\n";
        $ifelse .= "\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);\n";
        $contentElseInt = "\${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());";
        $contentIf = "\$errors = \$uploader->getErrors();\n";
        $contentIf .= "redirect_header('javascript:history.go(-1)', 3, \$errors);\n";
        $ifelse .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt);
        $contentElseExt = "\${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n";

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt);

        return $ret;
    }

    /*
    *  @public function getAdminXoopsCodeFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @param $formatUrl
    *  @return string
    */
    public function getAdminXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName, $formatUrl = false)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        if ($formatUrl) {
            $ret = $this->getAdminXoopsCodeSetVar($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])");
            $ret .= $this->pc->getPhpCodeCommentLine('Set Var', $fieldName);
        } else {
            $ret = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName);
        }
        $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true);
        $ret .= $this->getAdminXoopsCodeMediaUploader('uploader', "{$stuModuleDirname}_UPLOAD_FILES_PATH", $tableName, $moduleDirname);
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
        $ifelse .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElse);

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse);

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
        $ret = $this->pc->getPhpCodeCommentLine($comment = 'Set Vars', $var = '');
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->xc->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->xc->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->xc->getXoopsCodeUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->xc->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->xc->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->xc->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->xc->getXoopsCodeSimpleSetVar($tableName, $fieldName);
                        break;
                }
            }
        }

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
    public function getAdminXoopsCodeGetObjHandlerId($tableName, $fieldId, $t = '')
    {
        return "{$t}\${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});\n";
    }

    /*
    *  @public function getAdminCodeCaseDelete
    *  @param $tableName
    *  @param $language
    *  @param $fieldId
    *  @param $fieldMain
    *  @return string
    */
    public function getAdminCodeCaseDelete($language, $tableName, $fieldId, $fieldMain, $t = '')
    {
        $ccFieldId = $this->tf->getCamelCase($fieldId, false, true);
        $ret = $this->xc->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', $tableName.'Handler');

        $reqOk = "\$_REQUEST['ok']";
        $isset = $this->pc->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck = $this->xc->getXoopsCodeSecurityCheck();
        $xoopsSecurityErrors = $this->xc->getXoopsCodeSecurityErrors();
        $implode = $this->pc->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $this->xc->getXoopsCodeRedirectHeader($tableName, '', '3', $implode, true, $t."\t\t");

        $delete = $this->xc->getXoopsCodeDelete($tableName, $tableName, 'Obj', true);
        $condition = $this->pc->getPhpCodeConditions('!'.$xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t."\t");

        $redirectHeaderLanguage = $this->xc->getXoopsCodeRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK", true, $t."\t\t");
        $htmlErrors = $this->xc->getXoopsCodeHtmlErrors($tableName, true);
        $internalElse = $this->xc->getXoopsCodeTplAssign('error', $htmlErrors, false, $t."\t\t");
        $condition .= $this->pc->getPhpCodeConditions($delete, '', '', $redirectHeaderLanguage, $internalElse, $t."\t");

        $mainElse = $this->xc->getXoopsCodeXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'delete', $t."\t");
        $ret .= $this->pc->getPhpCodeConditions($isset, ' && ', "1 == {$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }

    /*
    *  @public function getAdminCodeCaseUpdate
    *  @param $language
    *  @param $tableName
    *  @param $fieldId
    *  @param $fieldName
    *  @return string
    */
    public function getAdminCodeCaseUpdate($language, $tableName, $fieldId, $fieldName, $t = '')
    {
        $ccFieldId = $this->tf->getCamelCase($fieldId, false, true);
        $get = $this->xc->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', $tableName.'Handler');
        $isset = $this->pc->getPhpCodeIsset($ccFieldId);
        $get = $this->xc->getXoopsCodeHandler($tableName, $fieldId, true);
        $ret = $this->pc->getPhpCodeConditions($isset, '', '', $get);
        $ret .= $this->xc->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->xc->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_UPDATE_OK");
        $ret .= $this->pc->getPhpCodeConditions($handlerInsert, '', '', $redirect);

        $ret .= $this->xc->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", false, $t."\t\t");

        return $this->pc->getPhpCodeCaseSwitch('update', $ret);
    }
}

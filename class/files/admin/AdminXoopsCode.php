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
 * @version         $Id: Axc.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class Axc.
 */
class AdminXoopsCode
{
    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return Axc
     */
    public static function getInstance()
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
        $aM = $t.'$adminMenu->addItemButton(';
        if ($type === 'add') {
            $ret = $aM."{$language}ADD_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
        } else {
            $ret = $aM."{$language}{$stuTableSoleName}_{$stuType}, '{$tableName}.php{$op}', '{$type}');\n";
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
    *  @public function getAxcAddInfoBox
    *  @param $language
    *  
    *  @return string
    */
    public function getAxcAddInfoBox($language, $t = '')
    {
        return "{$t}\$adminMenu->addInfoBox({$language});\n";
    }

    /*
    *  @public function getAxcAddInfoBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getAxcAddInfoBoxLine($language, $label = '', $var = '', $t = '')
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
    *  @public function getAxcAddConfigBoxLine
    *  @param $language
    *  @param $label
    *  @param $var
    *  
    *  @return string
    */
    public function getAxcAddConfigBoxLine($language, $label = '', $var = '', $t = '')
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
    *  @public function getAxcImageListSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getAxcImageListSetVar($moduleDirname, $tableName, $fieldName, $t = '')
    {
        $pCodeImageList = TDMCreatePhpCode::getInstance();
        $xCodeImageList = TDMCreateXoopsCode::getInstance();
        $ret = $pCodeImageList->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret .= $pCodeImageList->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath = "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret .= $xCodeImageList->getXcMediaUploader('uploader', $xRootPath, $moduleDirname, $t);
        $post = $pCodeImageList->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST').'[0]';
        $fetchMedia = self::getAxcFetchMedia('uploader', $post);
        $ifelse = $t."\t//".self::getAxcSetPrefix('uploader', "{$fieldName}_").";\n";
        $ifelse .= $t."\t//{$fetchMedia};\n";
        $contentElseInt = $xCodeImageList->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t."\t\t");
        $contentIf = $xCodeImageList->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t."\t\t");
        $contentIf .= $xCodeImageList->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t."\t\t");
        $ifelse .= $pCodeImageList->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t."\t");
        $contentElseExt = $xCodeImageList->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']", $t."\t");

        $ret .= $pCodeImageList->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /*
    *  @public function getAxcUploadImageSetVar
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldName
    *  @return string
    */
    public function getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain, $t = '')
    {
        $pCodeUploadImage = TDMCreatePhpCode::getInstance();
        $xCodeUploadImage = TDMCreateXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $pCodeUploadImage->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret .= $pCodeUploadImage->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xUploadImage = "{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $ret .= $xCodeUploadImage->getXcMediaUploader('uploader', $xUploadImage.".'/{$tableName}/'", $moduleDirname, $t);
        $post = $pCodeUploadImage->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST').'[0]';
        $fetchMedia = self::getAxcFetchMedia('uploader', $post);
        $file = $pCodeUploadImage->getPhpCodeGlobalsVariables('attachedfile', 'FILES')."['name']";
        $expr = '/^.+\.([^.]+)$/sU';
        $ifelse = $pCodeUploadImage->getPhpCodePregFunzions('extension', $expr, '', $file, 'replace', false, $t."\t");

        $ifelse .= $t."\t\$imgName = str_replace(' ', '', \$_POST['{$fieldMain}']).'.'.\$extension;\n";
        $ifelse .= self::getAxcSetPrefix('uploader', '$imgName', $t."\t").";\n";
        $ifelse .= $t."\t{$fetchMedia};\n";
        $contentElseInt = $xCodeUploadImage->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t."\t\t");
        $contentIf = $xCodeUploadImage->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t."\t\t");
        $contentIf .= $xCodeUploadImage->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t."\t\t");
        $ifelse .= $pCodeUploadImage->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t."\t");
        $contentElseExt = $xCodeUploadImage->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']", $t."\t");

        $ret .= $pCodeUploadImage->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /*
    *  @public function getAxcFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @param $formatUrl
    *  @return string
    */
    public function getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, $formatUrl = false, $t = '')
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = self::getAxcImageFileSetVar($moduleDirname, $stuModuleDirname.'_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t);

        return $ret;
    }

    /*
    *  @private function getAxcImageFileSetVar
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $fieldName
    *  @param $formatUrl
    *  @return string
    */
    private function getAxcImageFileSetVar($moduleDirname, $dirname = '', $tableName, $fieldName, $formatUrl = false, $t = '')
    {
        $pCodeFileSetVar = TDMCreatePhpCode::getInstance();
        $xCodeFileSetVar = TDMCreateXoopsCode::getInstance();
        $ret = '';
        $ifelse = '';
        $files = '';
        $post = $pCodeFileSetVar->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST').'[0]';
        $fetchMedia = self::getAxcFetchMedia('uploader', $post);
        if ($formatUrl) {
            $ret .= $xCodeFileSetVar->getXcSetVar($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])", $t);
            $ret .= $pCodeFileSetVar->getPhpCodeCommentLine('Set Var', $fieldName, $t);
            $ifelse .= $t."\t\t{$fetchMedia};\n";
        } else {
            $files = '/files';
            $ret .= $pCodeFileSetVar->getPhpCodeCommentLine('Set Var', $fieldName, $t);
            $ifelse .= $t."\t//".self::getAxcSetPrefix('uploader', "'{$fieldName}_'").";\n";
            $ifelse .= $t."\t//{$fetchMedia};\n";
        }
        $ret .= $pCodeFileSetVar->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $ret .= $xCodeFileSetVar->getXcMediaUploader('uploader', $dirname.".'/{$tableName}{$files}'", $moduleDirname, $t);
        $contentElse = $xCodeFileSetVar->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t."\t\t");
        $contentIf = $xCodeFileSetVar->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t."\t\t");
        $contentIf .= $xCodeFileSetVar->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t."\t\t");
        $ifelse .= $pCodeFileSetVar->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElse, $t."\t");

        $ret .= $pCodeFileSetVar->getPhpCodeConditions($fetchMedia, '', '', $t."\t".$fetchMedia.";\n", $ifelse, $t);

        return $ret;
    }

    /**
     *  @public function getAxcSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getAxcSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $xCodeSetVars = TDMCreateXoopsCode::getInstance();
        $ret = TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine($comment = 'Set Vars', $var = '');
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $xCodeSetVars->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= self::getAxcImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $xCodeSetVars->getXcUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= self::getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $xCodeSetVars->getXcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $xCodeSetVars->getXcTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $xCodeSetVars->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getAxcFetchMedia
     *
     *  @param $anchor
     *  @param $var
     *
     *  @return string
     */
    public function getAxcFetchMedia($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->fetchMedia({$var})";
    }

    /**
     *  @public function getAxcSetPrefix
     *
     *  @param $anchor
     *  @param $var
     *
     *  @return string
     */
    public function getAxcSetPrefix($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->setPrefix({$var})";
    }

    /**
     *  @public function getAxcGetObjHandlerId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getAxcGetObjHandlerId($tableName, $fieldId, $t = '')
    {
        return "{$t}\${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});\n";
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
        $phpCodeCaseDelete = TDMCreatePhpCode::getInstance();
        $xCodeCaseDelete = TDMCreateXoopsCode::getInstance();
        $ccFieldId = TDMCreateFile::getInstance()->getCamelCase($fieldId, false, true);
        $ret = $xCodeCaseDelete->getXcGet($tableName, $ccFieldId, 'Obj', $tableName.'Handler');

        $reqOk = "_REQUEST['ok']";
        $isset = $phpCodeCaseDelete->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck = $xCodeCaseDelete->getXcSecurityCheck();
        $xoopsSecurityErrors = $xCodeCaseDelete->getXcSecurityErrors();
        $implode = $phpCodeCaseDelete->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', $implode, true, $t."\t\t");

        $delete = $xCodeCaseDelete->getXcDelete($tableName, $tableName, 'Obj', 'Handler');
        $condition = $phpCodeCaseDelete->getPhpCodeConditions('!'.$xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t."\t");

        $redirectHeaderLanguage = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK", true, $t."\t\t");
        $htmlErrors = $xCodeCaseDelete->getXcHtmlErrors($tableName, true);
        $internalElse = $xCodeCaseDelete->getXcTplAssign('error', $htmlErrors, true, $t."\t\t");
        $condition .= $phpCodeCaseDelete->getPhpCodeConditions($delete, '', '', $redirectHeaderLanguage, $internalElse, $t."\t");

        $mainElse = $xCodeCaseDelete->getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'delete', $t."\t");
        $ret .= $phpCodeCaseDelete->getPhpCodeConditions($isset, ' && ', "1 == \${$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }
}

<?php

namespace XoopsModules\Tdmcreate\Files\Admin;

use XoopsModules\Tdmcreate;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class Axc.
 */
class AdminXoopsCode
{
    /**
     * @static function getInstance
     * @param null
     * @return AdminXoopsCode
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function getAdminTemplateMain
     * @param        $moduleDirname
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getAdminTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n";
    }

    /**
     * @public function getAdminTemplateMain
     * @param        $language
     * @param        $tableName
     * @param        $stuTableSoleName
     * @param string $op
     * @param string $type
     *
     * @param string $t
     * @return string
     */
    public function getAdminItemButton($language, $tableName, $stuTableSoleName, $op = '?op=new', $type = 'add', $t = '')
    {
        $stuType = mb_strtoupper($type);
        $aM      = $t . '$adminObject->addItemButton(';
        switch ($type) {
            case 'add';
                $ret = $aM . "{$language}ADD_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
            break;
            case 'samplebutton';
                $ret = $aM . "{$language}, '{$op}', 'add');\n";
                break;
            case 'default':
            default:
                $ret = $aM . "{$language}{$stuTableSoleName}_{$stuType}, '{$tableName}.php{$op}', '{$type}');\n";
            break;
        }

        return $ret;
    }

    /**
     * @public function getAdminAddNavigation
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getAdminDisplayButton($type, $t = '')
    {
        return "{$t}\$adminObject->displayButton('{$type}');\n";
    }

    /**
     * @public function getAdminAddNavigation
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getAdminDisplayNavigation($tableName, $t = '')
    {
        return "{$t}\$adminObject->displayNavigation('{$tableName}.php')";
    }

    /**
     * @public function getAxcAddInfoBox
     * @param        $language
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddInfoBox($language, $t = '')
    {
        return "{$t}\$adminObject->addInfoBox({$language});\n";
    }

    /**
     * @public function getAxcAddInfoBoxLine
     * @param        $language
     * @param string $label
     * @param string $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddInfoBoxLine($language, $label = '', $var = '', $t = '')
    {
        $aMenu = $t . '$adminObject->addInfoBoxLine(sprintf(';
        if ('' != $var) {
            $ret = $aMenu . " '<label>'.{$label}.'</label>', {$var}));\n";
        } else {
            $ret = $aMenu . " '<label>'.{$label}.'</label>'));\n";
        }

        return $ret;
    }

    /**
     * @public function getAxcAddConfigBoxLine
     * @param        $language
     * @param string $label
     * @param string $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddConfigBoxLine($language, $label = '', $var = '', $t = '')
    {
        $aMenu = $t . '$adminObject->addConfigBoxLine(';
        if ('' != $var) {
            $ret = $aMenu . "{$language}, '{$label}', {$var});\n";
        } else {
            $ret = $aMenu . "{$language}, '{$label}');\n";
        }

        return $ret;
    }

    /**
     * @public function getAxcImageListSetVar
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @return string
     */
    public function getAxcImageListSetVar($moduleDirname, $tableName, $fieldName, $t = '', $countUploader, $fieldMain)
    {
        $pCodeImageList = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeImageList = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ret            = $pCodeImageList->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret            .= $pCodeImageList->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath      = "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret            .= $xCodeImageList->getXcMediaUploader('uploader', $xRootPath, $t);
        $post           = $pCodeImageList->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia     = $this->getAxcFetchMedia('uploader', $post);
        $ifelse         = $t . "\t//" . $this->getAxcSetPrefix('uploader', "{$fieldName}_") . ";\n";
        $ifelse         .= $t . "\t//{$fetchMedia};\n";
        $contentElseInt = $xCodeImageList->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contentIf      = $xCodeImageList->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t . "\t\t");
        $contentIf      .= $xCodeImageList->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse         .= $pCodeImageList->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $contentElseExt = $xCodeImageList->getXcSetVar($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $pCodeImageList->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcUploadImageSetVar
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param        $fieldMain
     * @param string $t
     * @param int    $countUploader
     * @return string
     */
    public function getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain, $t = '', $countUploader)
    {
        $pCodeUploadImage = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeUploadImage = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $pCodeUploadImage->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret              .= $pCodeUploadImage->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xUploadImage     = "{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $ret              .= $xCodeUploadImage->getXcMediaUploader('uploader', $xUploadImage . " . '/{$tableName}/'", $t);
        $post             = $pCodeUploadImage->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia       = $this->getAxcFetchMedia('uploader', $post);
        $file             = $pCodeUploadImage->getPhpCodeGlobalsVariables('attachedfile', 'FILES') . "['name']";
        $expr             = '/^.+\.([^.]+)$/sU';
        $ifelse           = $pCodeUploadImage->getPhpCodePregFunzions('extension', $expr, '', $file, 'replace', false, $t . "\t");

        $ifelse         .= $t . "\t\$imgName = str_replace(' ', '', Request::getString('{$fieldMain}')) . '.' . \$extension;\n";
        $ifelse         .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse         .= $t . "\t{$fetchMedia};\n";
        $contentElseInt = $xCodeUploadImage->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contentIf      = $xCodeUploadImage->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t . "\t\t");
        $contentIf      .= $xCodeUploadImage->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse         .= $pCodeUploadImage->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $contentElseExt = $xCodeUploadImage->getXcSetVar($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $pCodeUploadImage->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcFileSetVar
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool   $formatUrl
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @return string
     */
    public function getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader, $fieldMain = '')
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $this->getAxcImageFileSetVar($moduleDirname, $stuModuleDirname . '_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t, $countUploader, $fieldMain);

        return $ret;
    }

    /**
     * @private function getAxcImageFileSetVar
     * @param        $moduleDirname
     * @param        $dirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool   $formatUrl
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @return string
     */
    private function getAxcImageFileSetVar($moduleDirname, $dirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader, $fieldMain)
    {
        $pCodeFileSetVar = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeFileSetVar = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ret             = '';
        $ifelse          = '';
        $files           = '';
        $contentIf       = '';

        if ($formatUrl) {
            $ret    .= $xCodeFileSetVar->getXcSetVar($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])", $t);
        }
        $ret            .= $pCodeFileSetVar->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret            .= $pCodeFileSetVar->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $ret            .= $xCodeFileSetVar->getXcMediaUploader('uploader', $dirname . " . '/{$tableName}{$files}/'", $t);
        $post           = $pCodeFileSetVar->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia     = $this->getAxcFetchMedia('uploader', $post);
        $file           = $pCodeFileSetVar->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $expr           = '/^.+\.([^.]+)$/sU';
        $ifelse         .= $pCodeFileSetVar->getPhpCodePregFunzions('extension', $expr, '', $file, 'replace', false, $t . "\t");

        $ifelse         .= $t . "\t\$imgName = str_replace(' ', '', Request::getString('{$fieldMain}')) . '.' . \$extension;\n";
        $ifelse         .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse         .= $t . "\t{$fetchMedia};\n";
        $contentElseInt = $xCodeFileSetVar->getXcSetVar($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contentIf      .= $xCodeFileSetVar->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, false, $t . "\t\t");
        $contentIf      .= $xCodeFileSetVar->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse         .= $pCodeFileSetVar->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $contentElseExt = $xCodeFileSetVar->getXcSetVar($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $pCodeFileSetVar->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarsObjects
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $fields
     * @return string
     */
    public function getAxcSetVarsObjects($moduleDirname, $tableName, $tableSoleName, $fields)
    {
        $xCodeSetVars  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ret           = Tdmcreate\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine($comment = 'Set Vars', $var = '');
        $fieldMain     = '';
        $countUploader = 0;
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $xCodeSetVars->getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->getAxcImageListSetVar($moduleDirname, $tableName, $fieldName, '', $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 12:
                        $ret .= $xCodeSetVars->getXcUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain, '', $countUploader);
                        $countUploader++;
                        break;
                    case 14:
                        $ret .= $xCodeSetVars->getXcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $xCodeSetVars->getXcTextDateSelectSetVar($tableName, $tableSoleName, $fieldName);
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
     * @public function getAxcMiscSetVar
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool   $formatUrl
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @return string
     */
    public function getAxcMiscSetVar($moduleDirname, $tableName, $fieldName, $fieldType, $t = '', $countUploader, $fieldMain = '')
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        switch ((int)$fieldType){
            case 2:
            case 3:
            case 4:
            case 5:
                $ret = $xc->getXcSetVar($tableName, $fieldName, "Request::getInt('{$fieldName}', 0)", $t);
                break;
            case 6:
            case 7:
            case 8:
                $ret = $xc->getXcSetVar($tableName, $fieldName, "Request::getFloat('{$fieldName}', 0)", $t);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                $ret = $xc->getXcSetVar($tableName, $fieldName, "Request::getString('{$fieldName}', '')", $t);
                break;
            case 0:
            default:
                //TODO: should be finally
                $ret = $xc->getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
                break;
        }

        return $ret;
    }

    /**
     * @public function getAxcFetchMedia
     *
     * @param        $anchor
     * @param        $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcFetchMedia($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->fetchMedia({$var})";
    }

    /**
     * @public function getAxcSetPrefix
     *
     * @param        $anchor
     * @param        $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcSetPrefix($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->setPrefix({$var})";
    }

    /**
     * @public function getAxcGetObjHandlerId
     *
     * @param string $tableName
     * @param string $fieldId
     *
     * @param string $t
     * @return string
     */
    public function getAxcGetObjHandlerId($tableName, $fieldId, $t = '')
    {
        return "{$t}\${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});\n";
    }

    /**
     * @public function getAdminCodeCaseDelete
     * @param        $language
     * @param        $tableName
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    public function getAdminCodeCaseDelete($language, $tableName, $fieldId, $fieldMain, $t = '')
    {
        $phpCodeCaseDelete = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeCaseDelete   = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ccFieldId         = Tdmcreate\Files\CreateFile::getInstance()->getCamelCase($fieldId, false, true);
        $ret               = $xCodeCaseDelete->getXcGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler');

        $reqOk                = "_REQUEST['ok']";
        $isset                = $phpCodeCaseDelete->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck   = $xCodeCaseDelete->getXcSecurityCheck();
        $xoopsSecurityErrors  = $xCodeCaseDelete->getXcSecurityErrors();
        $implode              = $phpCodeCaseDelete->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t\t");

        $delete    = $xCodeCaseDelete->getXcDelete($tableName, $tableName, 'Obj', 'Handler');
        $condition = $phpCodeCaseDelete->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t . "\t");

        $redirectHeaderLanguage = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK", true, $t . "\t\t");
        $htmlErrors             = $xCodeCaseDelete->getXcHtmlErrors($tableName, true);
        $internalElse           = $xCodeCaseDelete->getXcTplAssign('error', $htmlErrors, true, $t . "\t\t");
        $condition              .= $phpCodeCaseDelete->getPhpCodeConditions($delete, '', '', $redirectHeaderLanguage, $internalElse, $t . "\t");

        $mainElse = $xCodeCaseDelete->getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'delete', $t . "\t");
        $ret      .= $phpCodeCaseDelete->getPhpCodeConditions($isset, ' && ', "1 == \${$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }
}

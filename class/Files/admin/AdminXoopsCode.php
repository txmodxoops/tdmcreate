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
     * @param $type
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
     * @param string $label
     * @param string $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddInfoBoxLine($label = '', $var = '', $t = '')
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
     * @public function getAxcSetVarImageList
     * @param string $tableName
     * @param string $fieldName
     * @param string $t
     * @param int $countUploader
     * @return string
     */
    public function getAxcSetVarImageList($tableName, $fieldName, $t = '', $countUploader = 0)
    {
        $pCodeImageList = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeImageList = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ret            = $pCodeImageList->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret            .= $pCodeImageList->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath      = "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret            .= $xCodeImageList->getXcMediaUploader('uploader', $xRootPath, 'mimetypes_image', 'maxsize_image', $t);
        $post           = $pCodeImageList->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia     = $this->getAxcFetchMedia('uploader', $post);
        $ifelse         = $t . "\t//" . $this->getAxcSetPrefix('uploader', "{$fieldName}_") . ";\n";
        $ifelse         .= $t . "\t//{$fetchMedia};\n";
        $contentElseInt = $xCodeImageList->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contentIf      = $xCodeImageList->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $contentIf      .= $xCodeImageList->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse         .= $pCodeImageList->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $contentElseExt = $xCodeImageList->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $pCodeImageList->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarUploadImage
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param        $fieldMain
     * @param string $t
     * @param int    $countUploader
     * @return string
     */
    public function getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, $t = '', $countUploader = 0)
    {
        $pCodeUploadImage = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xCodeUploadImage = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret              = $pCodeUploadImage->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret              .= $pCodeUploadImage->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file             = $pCodeUploadImage->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret              .= $xCodeUploadImage->getXcEqualsOperator('$filename      ', $file, null, $t);
        $mimetype         = $pCodeUploadImage->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['type']";
        $ret              .= $xCodeUploadImage->getXcEqualsOperator('$imgMimetype   ', $mimetype, null, $t);
        $ret              .= $xCodeUploadImage->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret              .= $xCodeUploadImage->getXcEqualsOperator('$uploaderErrors', "''", null, $t);
        $xUploadImage     = "{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $ret              .= $xCodeUploadImage->getXcMediaUploader('uploader', $xUploadImage . " . '/{$tableName}/'", 'mimetypes_image', 'maxsize_image', $t);
        $post             = $pCodeUploadImage->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia       = $this->getAxcFetchMedia('uploader', $post);
        $expr             = '/^.+\.([^.]+)$/sU';
        $ifelse           = $pCodeUploadImage->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse           .= $t . "\t\$imgName = str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";
        $ifelse           .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse           .= $t . "\t{$fetchMedia};\n";
        $contentElseInt   = $xCodeUploadImage->getXcEqualsOperator('$savedFilename', '$uploader->getSavedFileName()', null, $t . "\t\t");
        $config           = $xCodeUploadImage->getXcGetConfig('maxwidth_image');
        $contentElseInt   .= $xCodeUploadImage->getXcEqualsOperator('$maxwidth ', "(int){$config}", null, $t . "\t\t");
        $config           = $xCodeUploadImage->getXcGetConfig('maxheight_image');
        $contentElseInt   .= $xCodeUploadImage->getXcEqualsOperator('$maxheight', "(int){$config}", null, $t . "\t\t");
        $resizer = $pCodeUploadImage->getPhpCodeCommentLine('Resize image', '', $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler               ', "new {$ucfModuleDirname}\Common\Resizer()", null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler->sourceFile   ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler->endFile      ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler->imageMimetype', '$imgMimetype', null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler->maxWidth     ', '$maxwidth', null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$imgHandler->maxHeight    ', '$maxheight', null, $t . "\t\t\t");
        $resizer .= $xCodeUploadImage->getXcEqualsOperator('$result                   ', '$imgHandler->resizeImage()', null, $t . "\t\t\t");
        $contentElseInt .= $pCodeUploadImage->getPhpCodeConditions('$maxwidth > 0 && $maxheight > 0', '', '', $resizer, false, $t . "\t\t");
        $contentElseInt .= $xCodeUploadImage->getXcSetVarObj($tableName, $fieldName, '$savedFilename', $t . "\t\t");
        $contentIf       = $xCodeUploadImage->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse         .= $pCodeUploadImage->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $ifelseExt       = $xCodeUploadImage->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contentElseExt  = $pCodeUploadImage->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contentElseExt .= $xCodeUploadImage->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

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
    public function getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader = 0, $fieldMain = '')
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $this->getAxcSetVarImageFile($stuModuleDirname . '_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t, $countUploader, $fieldMain, 'mimetypes_file', 'maxsize_file');

        return $ret;
    }

    /**
     * @private function getAxcSetVarImageFile
     * @param        $dirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool $formatUrl
     * @param string $t
     * @param int $countUploader
     * @param string $fieldMain
     * @param string $mimetype
     * @param string $maxsize
     * @return string
     */
    private function getAxcSetVarImageFile($dirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader = 0, $fieldMain = '', $mimetype = 'mimetypes_image', $maxsize = 'maxsize_image')
    {
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ret       = '';
        $files     = '';
        $contentIf = '';

        if ($formatUrl) {
            $ret    .= $xc->getXcSetVarObj($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])", $t);
        }
        $ret            .= $pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret            .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file           = $pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret            .= $xc->getXcEqualsOperator('$filename      ', $file, null, $t);
        $ret            .= $xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret            .= $xc->getXcMediaUploader('uploader', $dirname . " . '/{$tableName}{$files}/'", $mimetype, $maxsize, $t);
        $post           = $pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia     = $this->getAxcFetchMedia('uploader', $post);
        $expr           = '/^.+\.([^.]+)$/sU';
        $ifelse         = $pc->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse         .= $t . "\t\$imgName = str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";

        $ifelse         .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse         .= $t . "\t{$fetchMedia};\n";
        $contentElseInt = $xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contentIf      .= $xc->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse         .= $pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contentIf, $contentElseInt, $t . "\t");
        $ifelseExt       = $xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contentElseExt  = $pc->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contentElseExt .= $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contentElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarPassword
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getAxcSetVarPassword($tableName, $fieldName, $t = '')
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $cf  = Tdmcreate\Files\CreateFile::getInstance();
        $ccFieldId = $cf->getCamelCase($fieldName, false, true);
        $ret = $xc->getXcEqualsOperator("\${$ccFieldId}", "Request::getString('{$fieldName}', '')", '',$t);
        $contIf = $xc->getXcSetVarObj($tableName, $fieldName, "password_hash(\${$ccFieldId}, PASSWORD_DEFAULT)", $t . "\t");
        $ret .= $pc->getPhpCodeConditions("''", ' !== ', "\${$ccFieldId}",$contIf, false, $t);

        return $ret;
    }


    /**
     * @public function getAxcSetVarMisc
     * @param        $tableName
     * @param        $fieldName
     * @param $fieldType
     * @param string $t
     * @return string
     */
    public function getAxcSetVarMisc($tableName, $fieldName, $fieldType, $t = '')
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        switch ((int)$fieldType){
            case 2:
            case 3:
            case 4:
            case 5:
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getInt('{$fieldName}', 0)", $t);
                break;
            case 6:
            case 7:
            case 8:
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getFloat('{$fieldName}', 0)", $t);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}', '')", $t);
                break;
            case 0:
            default:
                //TODO: should be finally
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
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
        $ret               = $xCodeCaseDelete->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', '', $t);

        $reqOk                = "_REQUEST['ok']";
        $isset                = $phpCodeCaseDelete->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck   = $xCodeCaseDelete->getXcXoopsSecurityCheck();
        $xoopsSecurityErrors  = $xCodeCaseDelete->getXcXoopsSecurityErrors();
        $implode              = $phpCodeCaseDelete->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t\t");

        $delete    = $xCodeCaseDelete->getXcHandlerDelete($tableName, $tableName, 'Obj', 'Handler');
        $condition = $phpCodeCaseDelete->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t . "\t");

        $redirectHeaderLanguage = $xCodeCaseDelete->getXcRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK", true, $t . "\t\t");
        $htmlErrors             = $xCodeCaseDelete->getXcHtmlErrors($tableName, true);
        $internalElse           = $xCodeCaseDelete->getXcXoopsTplAssign('error', $htmlErrors, true, $t . "\t\t");
        $condition              .= $phpCodeCaseDelete->getPhpCodeConditions($delete, '', '', $redirectHeaderLanguage, $internalElse, $t . "\t");

        $mainElse = $xCodeCaseDelete->getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'delete', $t . "\t");
        $ret      .= $phpCodeCaseDelete->getPhpCodeConditions($isset, ' && ', "1 == \${$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }
}

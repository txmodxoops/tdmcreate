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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: UserPdf.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserPdf.
 */
class UserPdf extends TDMCreateFile
{
    /**
     * @var mixed
     */
    private $uc = null;

    /**
     * @var string
     */
    private $xc = null;

    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return UserPdf
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
     *  @public function write
     *  @param string $module
     *  @param mixed $table
     *  @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserPagesHeader
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     * @param $language
     * @return string
     */
    private function getUserPdfHeader($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = $this->xc->getXcSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $fileExist = $this->phpcode->getPhpCodeFileExists("\$tcpdf = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php'");
        $requireOnce = $this->phpcode->getPhpCodeIncludeDir('$tcpdf', '', true, true, 'require', "\t");
        $ret .= $this->xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');
        $redirectHeader = $this->xc->getXcRedirectHeader($tableName, '', $numb = '2', "{$language}NO_PDF_LIBRARY", true, "\t");
        $ret .= $this->phpcode->getPhpCodeConditions($fileExist, '', '', $requireOnce, $redirectHeader);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Instance of Handler');
        $ret .= $this->xc->getXoopsHandlerLine($moduleDirname, $tableName);
        $ret .= $this->xc->getXcGetVar($tableName, 'this', (string)$fieldId, false, '');

        return $ret;
    }

    /**
     *  @public function getAdminPagesList
     * @param $moduleDirname
     * @param $fields
     * @return string
     */
    public function getUserPdfTcpdf($moduleDirname, $fields)
    {
        $fieldId = $this->xc->getXcSaveFieldId($fields);
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $getVar = $this->xc->getXcGetVar('', 'pdfContent', $fieldName, true);
            switch ($fieldElement) {
                case 2:
                    if (false !== mb_strpos($fieldName, 'title') || false !== mb_strpos($fieldName, 'name') && '' == $fieldDefault) {
                        $ret .= $this->phpcode->getPhpCodeStripTags("pdfData['title']", $getVar);
                    }
                break;
                case 3:
                case 4:
                    $ret .= $this->phpcode->getPhpCodeStripTags("pdfData['content']", $getVar);
                break;
                case 8:
                    $ret .= $this->xc->getXcUnameFromId("pdfData['author']", $getVar);
                break;
                case 15:
                    $ret .= $this->xc->getXcFormatTimeStamp("pdfData['date']", $getVar);
                break;
            }
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Config');
        $ret .= $this->xc->getXcEqualsOperator("\$pdfData['creator'] ", "\$GLOBALS['xoopsConfig']['xoops_sitename']");
        $ret .= $this->xc->getXcEqualsOperator("\$pdfData['subject'] ", "\$GLOBALS['xoopsConfig']['slogan']");
        $ret .= $this->xc->getXcEqualsOperator("\$pdfData['keywords'] ", "\$GLOBALS['xoopsConfig']['keywords']");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Defines');
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_CREATOR", "\$pdfData['creator']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_AUTHOR", "\$pdfData['author']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_TITLE", "\$pdfData['title']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_STRING", "\$pdfData['subject']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_LOGO", "'logo.gif'");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_IMAGES_PATH", "XOOPS_ROOT_PATH.'/images/'");
        $ret .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', null, true);
        $ret .= $this->xc->getXcEqualsOperator('$content', "''");
        $ret .= $this->xc->getXcEqualsOperator('$content', "\$myts->undoHtmlSpecialChars(\$pdfData['content'])", '.');
        $ret .= $this->xc->getXcEqualsOperator('$content', '$myts->displayTarea($content)');
        $ret .= $this->xc->getXcEqualsOperator('$pdf', 'new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false)');
        $ret .= $this->xc->getXcEqualsOperator('$title', "\$myts->undoHtmlSpecialChars(\$pdfData['title'])");
        $ret .= $this->xc->getXcEqualsOperator('$keywords', "\$myts->undoHtmlSpecialChars(\$pdfData['keywords'])");
        $ret .= $this->xc->getXcEqualsOperator("\$pdfData['fontsize']", '12');
        $ret .= $this->phpcode->getPhpCodeCommentLine('For schinese');
        $ifLang = $this->getSimpleString("\$pdf->SetFont('gbsn00lp', '', \$pdfData['fontsize']);", "\t");
        $elseLang = $this->getSimpleString("\$pdf->SetFont(\$pdfData['fontname'], '', \$pdfData['fontsize']);", "\t");
        $ret .= $this->phpcode->getPhpCodeConditions('_LANGCODE', ' == ', "'cn'", $ifLang, $elseLang);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set document information');
        $ret .= $this->getSimpleString("\$pdf->SetCreator(\$pdfData['creator']);");
        $ret .= $this->getSimpleString("\$pdf->SetAuthor(\$pdfData['author']);");
        $ret .= $this->getSimpleString('$pdf->SetTitle($title);');
        $ret .= $this->getSimpleString('$pdf->SetKeywords($keywords);');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set default header data');
        $ret .= $this->getSimpleString("\$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, {$stuModuleDirname}_HEADER_TITLE, {$stuModuleDirname}_HEADER_STRING);");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set margins');
        $ret .= $this->getSimpleString('$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set auto page breaks');
        $ret .= $this->getSimpleString('$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);');
        $ret .= $this->getSimpleString('$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);');
        $ret .= $this->getSimpleString('$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);');
        $ret .= $this->getSimpleString('$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor');
        $ifLang = $this->getSimpleString("\$pdf->setHeaderFont(array('gbsn00lp', '', \$pdfData['fontsize']));", "\t");
        $ifLang .= $this->getSimpleString("\$pdf->setFooterFont(array('gbsn00lp', '', \$pdfData['fontsize']));", "\t");
        $elseLang = $this->getSimpleString("\$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));", "\t");
        $elseLang .= $this->getSimpleString("\$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));", "\t");
        $ret .= $this->phpcode->getPhpCodeConditions('_LANGCODE', ' == ', "'cn'", $ifLang, $elseLang);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Set some language-dependent strings (optional)');
        $fileExist = $this->phpcode->getPhpCodeFileExists("\$lang = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/lang/eng.php'");
        $contIf = $this->phpcode->getPhpCodeIncludeDir('$lang', '', true, false, 'require', "\t");
        $contIf .= $this->getSimpleString('$pdf->setLanguageArray($l);', "\t");
        $ret .= $this->phpcode->getPhpCodeConditions("@{$fileExist}", '', '', $contIf);

        return $ret;
    }

    /**
     * @private function getUserPdfFooter
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getUserPdfFooter($moduleDirname, $tableName)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Initialize document');
        $ret .= $this->getSimpleString('$pdf->AliasNbPages();');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Add Page document');
        $ret .= $this->getSimpleString('$pdf->AddPage();');
        $ret .= $this->getSimpleString("\$pdf->writeHTMLCell(\$w=0, \$h=0, \$x='', \$y='', \$content, \$border=0, \$ln=1, \$fill=0, \$reseth=true, \$align='', \$autopadding=true);");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Pdf Filename');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Output');
        $ret .= $this->xc->getXcTplAssign('pdfoutput', "\$pdf->Output('{$tableName}.pdf', 'I')");
        $ret .= $this->xc->getXcTplDisplay($moduleDirname . '_pdf.tpl', '', false);

        return $ret;
    }

    /**
     *  @public function render
     *  @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserPdfHeader($moduleDirname, $tableName, $fields, $language);
        $content .= $this->getUserPdfTcpdf($moduleDirname, $fields);
        $content .= $this->getUserPdfFooter($moduleDirname, $tableName);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

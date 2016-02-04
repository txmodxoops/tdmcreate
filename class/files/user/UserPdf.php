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
 * @version         $Id: UserPdf.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserPdf.
 */
class UserPdf extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $usercode = null;

    /*
    * @var mixed
    */
    private $phpcode = null;

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
        parent::__construct();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->usercode = UserXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserPdf
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
    *  @public function write
    *  @param string $module
    *  @param mixed $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserPagesHeader
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getUserPdfHeader($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = $this->xoopscode->getXoopsCodeSaveFieldId($fields);
        $ret = $this->getInclude();
        $fileExist = $this->phpcode->getPhpCodeFileExists("\$tcpdf = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php'");
        $requireOnce = $this->phpcode->getPhpCodeIncludeDir('$tcpdf', '', true, true, 'require');
        $redirectHeader = $this->xoopscode->getXoopsCodeRedirectHeader($tableName, "?{$fieldId}=\${$fieldId}", $numb = '2', "{$language}NO_PDF_LIBRARY");
        $ret .= $this->phpcode->getPhpCodeConditions($fileExist, '', '', $requireOnce, $redirectHeader, $t = '');
        $ret .= $this->getCommentLine('Get Instance of Handler');
        $ret .= $this->xoopscode->getXoopsHandlerLine($moduleDirname, $tableName);
        $ret .= $this->xoopscode->getXoopsCodeGet($tableName, "\$this->getVar('{$fieldId}')", '', true);

        return $ret;
    }

    /*
    *  @public function getAdminPagesList
    *  @param string $tableName
    *  @param string $language
    */
    /**
     * @param $module
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserPdfTcpdf($moduleDirname, $fields)
    {
        $fieldId = $this->xoopscode->getXoopsCodeSaveFieldId($fields);
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $getVar = $this->xoopscode->getXoopsCodeGetVar('', 'pdfContent', $fieldName, true);
            switch ($fieldElement) {
                case 2:
                    if (strstr($fieldName, 'title') || strstr($fieldName, 'name') && $fieldDefault == '') {
                        $ret .= $this->phpcode->getPhpCodeStripTags("pdfData['title']", $getVar);
                    }
                break;
                case 3:
                case 4:
                    $ret .= $this->phpcode->getPhpCodeStripTags("pdfData['content']", $getVar);
                break;
                case 8:
                    $ret .= $this->xoopscode->getXoopsCodeUnameFromId("pdfData['author']", $getVar);
                break;
                case 15:
                    $ret .= $this->xoopscode->getXoopsCodeFormatTimeStamp("pdfData['date']", $getVar);
                break;
            }
        }
        $ret .= $this->getCommentLine('Get Config');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\$pdfData['creator'] ", "\$GLOBALS['xoopsConfig']['xoops_sitename']");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\$pdfData['subject'] ", "\$GLOBALS['xoopsConfig']['slogan']");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\$pdfData['keywords'] ", "\$GLOBALS['xoopsConfig']['keywords']");
        $ret .= $this->getCommentLine('Defines');
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_CREATOR", "\$pdfData['creator']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_AUTHOR", "\$pdfData['author']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_TITLE", "\$pdfData['title']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_STRING", "\$pdfData['subject']");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_HEADER_LOGO", "'logo.gif'");
        $ret .= $this->phpcode->getPhpCodeDefine("{$stuModuleDirname}_IMAGES_PATH", "XOOPS_ROOT_PATH.'/images/'");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$myts ', 'MyTextSanitizer::getInstance()', true);
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$content ', "''");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$content .', "\$myts->undoHtmlSpecialChars(\$pdfData['content'])");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$content ', '$myts->displayTarea($content)');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$pdf ', 'new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false)');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$title ', "\$myts->undoHtmlSpecialChars(\$pdfData['title'])");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$keywords ', "\$myts->undoHtmlSpecialChars(\$pdfData['keywords'])");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\$pdfData['fontsize'] ", '12');
        $ret .= $this->getCommentLine('For schinese');
        $ifLang = $this->getSimpleString("\$pdf->SetFont('gbsn00lp', '', \$pdfData['fontsize']);");
        $elseLang = $this->getSimpleString("\$pdf->SetFont(\$pdfData['fontname'], '', \$pdfData['fontsize']);");
        $ret .= $this->phpcode->getPhpCodeConditions('_LANGCODE', ' == ', "'cn'", $ifLang, $elseLang);
        $ret .= $this->getCommentLine('Set document information');
        $ret .= $this->getSimpleString("\$pdf->SetCreator(\$pdfData['creator']);");
        $ret .= $this->getSimpleString("\$pdf->SetAuthor(\$pdfData['author']);");
        $ret .= $this->getSimpleString('$pdf->SetTitle($title);');
        $ret .= $this->getSimpleString('$pdf->SetKeywords($keywords);');
        $ret .= $this->getCommentLine('Set default header data');
        $ret .= $this->getSimpleString("\$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, {$stuModuleDirname}_HEADER_TITLE, {$stuModuleDirname}_HEADER_STRING);");
        $ret .= $this->getCommentLine('Set margins');
        $ret .= $this->getSimpleString('$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);');
        $ret .= $this->getCommentLine('Set auto page breaks');
        $ret .= $this->getSimpleString('$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);');
        $ret .= $this->getSimpleString('$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);');
        $ret .= $this->getSimpleString('$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);');
        $ret .= $this->getSimpleString('$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor');
        $ifLang = $this->getSimpleString("\$pdf->setHeaderFont(array('gbsn00lp', '', \$pdfData['fontsize']));");
        $ifLang .= $this->getSimpleString("\$pdf->setFooterFont(array('gbsn00lp', '', \$pdfData['fontsize']));");
        $elseLang = $this->getSimpleString("\$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));");
        $elseLang .= $this->getSimpleString("\$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));");
        $ret .= $this->phpcode->getPhpCodeConditions('_LANGCODE', ' == ', "'cn'", $ifLang, $elseLang);
        $ret .= $this->getCommentLine('Set some language-dependent strings (optional)');
        $fileExist = $this->phpcode->getPhpCodeFileExists("\$lang = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/lang/eng.php')");
        $contIf = $this->phpcode->getPhpCodeIncludeDir('$lang', '', true, false, 'require');
        $contIf .= $this->getSimpleString('$pdf->setLanguageArray($l);');
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
        $ret = $this->getCommentLine('Initialize document');
        $ret .= $this->getSimpleString('$pdf->AliasNbPages();');
        $ret .= $this->getCommentLine('Add Page document');
        $ret .= $this->getSimpleString('$pdf->AddPage();');
        $ret .= $this->getSimpleString("\$pdf->writeHTMLCell(\$w=0, \$h=0, \$x='', \$y='', \$content, \$border=0, \$ln=1, \$fill=0, \$reseth=true, \$align='', \$autopadding=true);");
        $ret .= $this->getCommentLine('Pdf Filename');
        $ret .= $this->getCommentLine('Output');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('pdfoutput', "\$pdf->Output('{$tableName}.pdf', 'I')");
        $ret .= $this->xoopscode->getXoopsCodeTplDisplay("{$moduleDirname}_pdf.tpl");

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
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

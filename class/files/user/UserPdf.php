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
    public function getUserPdfTcpdf($moduleDirname, $tableName, $fields, $language)
    {
        $fieldId = $this->xoopscode->getXoopsCodeSaveFieldId($fields);
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = <<<EOT
include  __DIR__ . '/header.php';
if(file_exists(\$tcpdf = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php')) {
	require_once \$tcpdf;
} else {
	redirect_header("{$tableName}.php?{$fieldId}=\${$fieldId}", 2, {$language}NO_PDF_LIBRARY);
}
//
\${$tableName}Handler =& {$moduleDirname}->getHandler('{$tableName}');
\$pdfContent = \${$tableName}Handler->get(\$this->getVar('{$fieldId}'));\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            switch ($fieldElement) {
                case 2:
                    if (strstr($fieldName, 'title') || strstr($fieldName, 'name') && $fieldDefault == '') {
                        $ret .= <<<EOT
\$pdfData['title'] = strip_tags(\$pdfContent->getVar('{$fieldName}'));\n
EOT;
                    }
                break;
                case 3:
                case 4:
                    $ret .= <<<EOT
\$pdfData['content'] = strip_tags(\$pdfContent->getVar('{$fieldName}'));\n
EOT;
                break;
                case 8:
                    $ret .= <<<EOT
\$pdfData['author'] = XoopsUser::getUnameFromId(\$pdfContent->getVar('{$fieldName}'));\n
EOT;
                break;
                case 15:
                    $ret .= <<<EOT
\$pdfData['date'] = formatTimeStamp(\$pdfContent->getVar('{$fieldName}'));\n
EOT;
                break;
            }
        }
        $ret .= <<<EOT
//
\$pdfData['creator'] = \$GLOBALS['xoopsConfig']['xoops_sitename'];
\$pdfData['subject'] = \$GLOBALS['xoopsConfig']['slogan'];
\$pdfData['keywords'] = {$moduleDirname}->getConfig('keywords');
//
define('{$stuModuleDirname}_CREATOR', \$pdfData['creator']);
define('{$stuModuleDirname}_AUTHOR', \$pdfData['author']);
define('{$stuModuleDirname}_HEADER_TITLE', \$pdfData['title']);
define('{$stuModuleDirname}_HEADER_STRING', \$pdfData['subject']);
define('{$stuModuleDirname}_HEADER_LOGO', 'logo.gif');
define('{$stuModuleDirname}_IMAGES_PATH', XOOPS_ROOT_PATH.'/images/');

\$myts =& MyTextSanitizer::getInstance();
\$content = '';
\$content .= \$myts->undoHtmlSpecialChars(\$pdfData['content']);
\$content = \$myts->displayTarea(\$content);

\$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false);
\$title = \$myts->undoHtmlSpecialChars(\$pdfData['title']);
\$keywords = \$myts->undoHtmlSpecialChars(\$pdfData['keywords']);
\$pdfData['fontsize'] = 12;
// For schinese
if (_LANGCODE == "cn") {
	\$pdf->SetFont('gbsn00lp', '', \$pdfData['fontsize']);
} else {
	\$pdf->SetFont(\$pdfData['fontname'], '', \$pdfData['fontsize']);
}
// set document information
\$pdf->SetCreator(\$pdfData['creator']);
\$pdf->SetAuthor(\$pdfData['author']);
\$pdf->SetTitle(\$title);
\$pdf->SetKeywords(\$keywords);

// set default header data
\$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, {$stuModuleDirname}_HEADER_TITLE, {$stuModuleDirname}_HEADER_STRING);

//set margins
\$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
//set auto page breaks
\$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
\$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
\$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
\$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

// For schinese
if (_LANGCODE == "cn") {
	\$pdf->setHeaderFont(array('gbsn00lp', '', \$pdfData['fontsize']));
	\$pdf->setFooterFont(array('gbsn00lp', '', \$pdfData['fontsize']));
} else {
	\$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	\$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
}
// set some language-dependent strings (optional)
if (@file_exists(\$lang = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/lang/eng.php')) {
    require_once(\$lang);
    \$pdf->setLanguageArray(\$l);
}
// Initialize document
\$pdf->AliasNbPages();
// Add Page document
\$pdf->AddPage();
\$pdf->writeHTMLCell(\$w=0, \$h=0, \$x='', \$y='', \$content, \$border=0, \$ln=1, \$fill=0, \$reseth=true, \$align='', \$autopadding=true);
// Pdf Filename
// Output
\$GLOBALS['xoopsTpl']->assign('pdfoutput', \$pdf->Output('{$tableName}.pdf', 'I'));
\$GLOBALS['xoopsTpl']->display('db:{$moduleDirname}_pdf.tpl');\n
EOT;

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
        $content .= $this->getUserPdfTcpdf($moduleDirname, $tableName, $fields, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

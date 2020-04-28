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
 * My Module 3 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule3
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use Xmf\Request;
use XoopsModules\Mymodule3;
use XoopsModules\Mymodule3\Constants;

require __DIR__ . '/header.php';
$tfId = Request::getInt('tf_id');
if (file_exists($tcpdf = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php')) {
	require_once $tcpdf;
} else {
	redirect_header('testfields.php', 2, _MA_MYMODULE3_NO_PDF_LIBRARY);
}
// Get Instance of Handler
$testfieldsHandler = $helper->getHandler('testfields');

$pdfData['content'] = strip_tags($testfieldsHandler->getVar('tf_textarea'));
$pdfData['content'] = strip_tags($testfieldsHandler->getVar('tf_dhtml'));
$pdfData['author']  = \XoopsUser::getUnameFromId($testfieldsHandler->getVar('tf_user'));
$pdfData['date']    = formatTimeStamp($testfieldsHandler->getVar('tf_textdateselect'), 's');

// Get Config
$pdfData['creator']   = $GLOBALS['xoopsConfig']['xoops_sitename'];
$pdfData['subject']   = $GLOBALS['xoopsConfig']['slogan'];
$pdfData['keywords']  = $GLOBALS['xoopsConfig']['keywords'];
// Defines
define('MYMODULE3_CREATOR', $pdfData['creator']);
define('MYMODULE3_AUTHOR', $pdfData['author']);
define('MYMODULE3_HEADER_TITLE', $pdfData['title']);
define('MYMODULE3_HEADER_STRING', $pdfData['subject']);
define('MYMODULE3_HEADER_LOGO', 'logo.gif');
define('MYMODULE3_IMAGES_PATH', XOOPS_ROOT_PATH.'/images/');
$myts = MyTextSanitizer::getInstance();
$content = '';
$content .= $myts->undoHtmlSpecialChars($pdfData['content']);
$content = $myts->displayTarea($content);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false);
$title = $myts->undoHtmlSpecialChars($pdfData['title']);
$keywords = $myts->undoHtmlSpecialChars($pdfData['keywords']);
$pdfData['fontsize'] = 12;
// For schinese
if (_LANGCODE == 'cn') {
	$pdf->SetFont('gbsn00lp', '', $pdfData['fontsize']);
} else {
	$pdf->SetFont($pdfData['fontname'], '', $pdfData['fontsize']);
}
// Set document information
$pdf->SetCreator($pdfData['creator']);
$pdf->SetAuthor($pdfData['author']);
$pdf->SetTitle($title);
$pdf->SetKeywords($keywords);
// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, MYMODULE3_HEADER_TITLE, MYMODULE3_HEADER_STRING);
// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
// Set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
if (_LANGCODE == 'cn') {
	$pdf->setHeaderFont(array('gbsn00lp', '', $pdfData['fontsize']));
	$pdf->setFooterFont(array('gbsn00lp', '', $pdfData['fontsize']));
} else {
	$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
}
// Set some language-dependent strings (optional)
$lang = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/lang/eng.php';
if (@file_exists($lang)) {
	require_once $lang;
	$pdf->setLanguageArray($lang);
}
// Initialize document
$pdf->AliasNbPages();
// Add Page document
$pdf->AddPage();
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $content, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
// Pdf Filename
// Output
$GLOBALS['xoopsTpl']->assign('pdfoutput', $pdf->Output('testfields.pdf', 'I'));
$GLOBALS['xoopsTpl']->display('db:mymodule3_pdf.tpl');

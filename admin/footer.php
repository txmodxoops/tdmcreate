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
 * @version         $Id: footer.php 12207 2013-10-23 02:46:52Z beckmi $
 */
$GLOBALS['xoopsTpl']->assign('module_name', $GLOBALS['xoopsModule']->getVar('name'));
if ( isset($template_main)  ) {
    $GLOBALS['xoopsTpl']->display("db:{$template_main}");
}
xoops_cp_footer();
?>
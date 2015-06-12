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
 * @version         $Id: TDMCreateArchitecture.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include __DIR__ . '/autoload.php';
/**
 * Class TDMCreateArchitecture
 */
class TDMCreateArchitecture extends TDMCreateStructure
{
    /*
    * @var mixed
    */
    private $tdmcreate;
	
    /*
    * @var mixed
    */
    private $structure;

    /*
    *  @public function constructor class
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tdmcreate = TDMCreateHelper::getInstance();
		$this->tdmcfile  = TDMCreateFile::getInstance();
        $this->structure = TDMCreateStructure::getInstance();
        $this->structure->setUploadPath(TDMC_UPLOAD_REPOSITORY_PATH);
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateArchitecture
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
    *  @public function createBaseFoldersFiles
    *  @param string $module
    */
    /**
     * @param $module
     */
    public function createBaseFoldersFiles($module)
    {
        // Module
        $modId    = $module->getVar('mod_id');
        $language = $GLOBALS['xoopsConfig']['language'];
        // Id of tables
        $tables = $this->tdmcfile->getTableTables($modId);
        //
        $table = null;
        foreach (array_keys($tables) as $t) {
            $tableId     = $tables[$t]->getVar('table_id');
            $tableName   = $tables[$t]->getVar('table_name');
            $tableAdmin  = $tables[$t]->getVar('table_admin');
            $tableUser   = $tables[$t]->getVar('table_user');
            $tableBlocks = $tables[$t]->getVar('table_blocks');
            $table       = $this->tdmcreate->getHandler('tables')->get($tableId);
        }
        //
        $indexFile       = XOOPS_UPLOAD_PATH . '/index.html';
        $stlModuleAuthor = str_replace(' ', '', strtolower($module->getVar('mod_author')));
        $this->structure->setModuleName($module->getVar('mod_dirname'));
        $uploadPath = $this->structure->getUploadPath();
        // Creation of "module" folder in the Directory repository
        $this->structure->makeDir($uploadPath . '/' . $this->structure->getModuleName());
		if (1 != $module->getVar('mod_user')) {
			// Copied of index.html file in "root module" folder
			$this->structure->copyFile('', $indexFile, 'index.html');
		}
        if (1 == $module->getVar('mod_admin')) {
            // Creation of "admin" folder and index.html file
            $this->structure->makeDirAndCopyFile('admin', $indexFile, 'index.html');
        }
        if (1 == $module->getVar('mod_blocks')) {
            // Creation of "blocks" folder and index.html file
            $this->structure->makeDirAndCopyFile('blocks', $indexFile, 'index.html');
        }
        // Creation of "class" folder and index.html file
        $this->structure->makeDirAndCopyFile('class', $indexFile, 'index.html');
        // Creation of "assets" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets', $indexFile, 'index.html');
        // Creation of "assets/css" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/css', $indexFile, 'index.html');
        // Creation of "assets/icons" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/icons', $indexFile, 'index.html');
        // Creation of "assets/icons/16" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/icons/16', $indexFile, 'index.html');
        // Creation of "assets/icons/32" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/icons/32', $indexFile, 'index.html');
        // Creation of "assets/images" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/images', $indexFile, 'index.html');
        // Creation of "assets/js" folder and index.html file
        $this->structure->makeDirAndCopyFile('assets/js', $indexFile, 'index.html');
        //Copy the logo of the module
        $modImage = str_replace(' ', '', strtolower($module->getVar('mod_image')));
        $this->structure->copyFile('assets/images', TDMC_UPLOAD_IMGMOD_PATH . '/' . $modImage, $modImage);
        // Copy of 'module_author_logo.gif' file in uploads dir
        $logoGifFrom = TDMC_UPLOAD_IMGMOD_PATH . '/' . $stlModuleAuthor . '_logo.gif';
        // If file exists
        if (!file_exists($logoGifFrom)) {
            // Rename file
            $copyFile    = TDMC_IMAGES_LOGOS_URL . '/xoopsdevelopmentteam_logo.gif';
            $copyNewFile = $logoGifFrom;
            copy($copyFile, $copyNewFile);
        } else {
            // Copy file
            $copyFile    = TDMC_IMAGES_LOGOS_URL . '/' . $stlModuleAuthor . '_logo.gif';
            $copyNewFile = $logoGifFrom;
            copy($copyFile, $copyNewFile);
        }
        // Creation of 'module_author_logo.gif' file
        $this->structure->copyFile('assets/images', $copyNewFile, $stlModuleAuthor . '_logo.gif');
        // Creation of 'docs' folder and index.html file
        $this->structure->makeDirAndCopyFile('docs', $indexFile, 'index.html');
        // Creation of 'credits.txt' file
        $this->structure->copyFile('docs', TDMC_DOCS_PATH . '/credits.txt', 'credits.txt');
        // Creation of 'install.txt' file
        $this->structure->copyFile('docs', TDMC_DOCS_PATH . '/install.txt', 'install.txt');
        // Creation of 'lang_diff.txt' file
        $this->structure->copyFile('docs', TDMC_DOCS_PATH . '/lang_diff.txt', 'lang_diff.txt');
        // Creation of 'license.txt' file
        $this->structure->copyFile('docs', TDMC_DOCS_PATH . '/license.txt', 'license.txt');
        // Creation of 'readme.txt' file
        $this->structure->copyFile('docs', TDMC_DOCS_PATH . '/readme.txt', 'readme.txt');
        // Creation of "include" folder and index.html file
        $this->structure->makeDirAndCopyFile('include', $indexFile, 'index.html');
        // Creation of "language" folder and index.html file
        $this->structure->makeDirAndCopyFile('language', $indexFile, 'index.html');
        // Creation of 'default english' folder
        if ($language != 'english') {
            // Creation of "language/local_language" folder and index.html file
            $this->structure->makeDirAndCopyFile('language/' . $language, $indexFile, 'index.html');
            // Creation of "language/local_language/help" folder and index.html file
            $this->structure->makeDirAndCopyFile('language/' . $language . '/help', $indexFile, 'index.html');
        }
        // Creation of "english" folder and index.html file
        $this->structure->makeDirAndCopyFile('language/english', $indexFile, 'index.html');
        // Creation of "language/english/help" folder and index.html file
        $this->structure->makeDirAndCopyFile('language/english/help', $indexFile, 'index.html');
		// Creation of "preloads" folder and index.html file
        $this->structure->makeDirAndCopyFile('preloads', $indexFile, 'index.html');
        if (1 == $module->getVar('mod_admin')) {
            // Creation of "templates" folder and index.html file
            $this->structure->makeDirAndCopyFile('templates', $indexFile, 'index.html');
        }
        if (1 == $module->getVar('mod_admin')) {
            // Creation of "templates/admin" folder and index.html file
            $this->structure->makeDirAndCopyFile('templates/admin', $indexFile, 'index.html');
        }
        if ($table->getVar('table_name') != null) {
            if ((1 == $module->getVar('mod_blocks')) && (1 == $table->getVar('table_blocks'))) {
                // Creation of "templates/blocks" folder and index.html file
                $this->structure->makeDirAndCopyFile('templates/blocks', $indexFile, 'index.html');
            }
            // Creation of "sql" folder and index.html file
            $this->structure->makeDirAndCopyFile('sql', $indexFile, 'index.html');
            if ((1 == $module->getVar('mod_notifications')) && (1 == $table->getVar('table_notifications'))) {
                if ($language != 'english') {
                    // Creation of "language/local_language/mail_template" folder and index.html file
                    $this->structure->makeDirAndCopyFile('language/' . $language . '/mail_template', $indexFile, 'index.html');
                } else {
					// Creation of "language/english/mail_template" folder and index.html file
					$this->structure->makeDirAndCopyFile('language/english/mail_template', $indexFile, 'index.html');
				}
            }
        }		
    }

    /*
    *  @public function createFilesToBuilding
    *  @param string $module
    */
    /**
     * @param $module
     * @return array
     */
    public function createFilesToBuilding($module)
    {
        // Module
        $modId          = $module->getVar('mod_id');
        $moduleDirname  = $module->getVar('mod_dirname');
        $icon32         = 'assets/icons/32';
        $tables 		= $this->tdmcfile->getTableTables($modId);
        $ret 			= array();
        //
        $table = array();
        foreach (array_keys($tables) as $t) {
            $tableMid           = $tables[$t]->getVar('table_mid');
            $tableId            = $tables[$t]->getVar('table_id');
            $tableName          = $tables[$t]->getVar('table_name');
            $tableImage         = $tables[$t]->getVar('table_image');
            $tableAdmin         = $tables[$t]->getVar('table_admin');
            $tableUser          = $tables[$t]->getVar('table_user');
            $tableBlocks        = $tables[$t]->getVar('table_blocks');
            $tableSearch        = $tables[$t]->getVar('table_search');
            $tableComments      = $tables[$t]->getVar('table_comments');
            $tableNotifications = $tables[$t]->getVar('table_notifications');
            $tablePermissions   = $tables[$t]->getVar('table_permissions');
            // Get Table Object
            $table = $this->tdmcreate->getHandler('tables')->get($tableId);
            // Copy of tables images file
            if (file_exists($uploadTableImage = TDMC_UPLOAD_IMGTAB_PATH . '/' . $tableImage)) {
                $this->structure->copyFile($icon32, $uploadTableImage, $tableImage);
            } elseif (file_exists($uploadTableImage = XOOPS_ICONS32_PATH . '/' . $tableImage)) {
                $this->structure->copyFile($icon32, $uploadTableImage, $tableImage);
            }
            // Creation of admin files
            if (1 == $tableAdmin) {
                // Admin Pages File
                $adminPages = AdminPages::getInstance();
                $adminPages->write($module, $table);
                $ret[] = $adminPages->renderFile($tableName . '.php');
                // Admin Templates File
                $adminTemplatesPages = TemplatesAdminPages::getInstance();
                $adminTemplatesPages->write($module, $table);
                $ret[] = $adminTemplatesPages->renderFile($moduleDirname . '_admin_' . $tableName . '.tpl');
            }
            // Creation of blocks
            if (1 == $tableBlocks) {
                // Blocks Files
                $blocksFiles = BlocksFiles::getInstance();
                $blocksFiles->write($module, $table);
                $ret[] = $blocksFiles->renderFile($tableName . '.php');
                // Templates Blocks Files
                $templatesBlocks = TemplatesBlocks::getInstance();
                $templatesBlocks->write($module, $table);
                $ret[] = $templatesBlocks->renderFile($moduleDirname . '_block_' . $tableName . '.tpl');
            }
            // Creation of classes
            if (1 == $tableAdmin || 1 == $tableUser) {
                // Class Files
                $classFiles = ClassFiles::getInstance();
                $classFiles->write($module, $table, $tables);
                $ret[] = $classFiles->renderFile($tableName . '.php');				
            }
            // Creation of user files
            if (1 == $tableUser) {
                // User Pages File
                $userPages = UserPages::getInstance();
                $userPages->write($module, $table, $tableName . '.php');
                $ret[] = $userPages->renderFile();
                // User Templates File
                $userTemplatesPages = TemplatesUserPages::getInstance();
                $userTemplatesPages->write($module, $table);
                $ret[] = $userTemplatesPages->renderFile($moduleDirname . '_' . $tableName . '.tpl');
				// User List Templates File
                $userTemplatesPagesList = TemplatesUserPagesList::getInstance();
                $userTemplatesPagesList->write($module, $table);
                $ret[] = $userTemplatesPagesList->renderFile($moduleDirname . '_' . $tableName . '_list' . '.tpl');
            }
        }
        // Language Modinfo File
        $languageModinfo = LanguageModinfo::getInstance();
        $languageModinfo->write($module, $table, $tables, 'modinfo.php');
        $ret[] = $languageModinfo->render();
        if (1 == $module->getVar('mod_admin')) {
            // Admin Header File
            $adminHeader = AdminHeader::getInstance();
            $adminHeader->write($module, $table, $tables, 'header.php');
            $ret[] = $adminHeader->render();
            // Admin Index File
            $adminIndex = AdminIndex::getInstance();
            $adminIndex->write($module, $tables, 'index.php');
            $ret[] = $adminIndex->render();
            // Admin Menu File
            $adminMenu = AdminMenu::getInstance();
            $adminMenu->write($module, $table, $tables, 'menu.php');
            $ret[] = $adminMenu->render();
            // Admin About File
            $adminAbout = AdminAbout::getInstance();
            $adminAbout->write($module, 'about.php');
            $ret[] = $adminAbout->render();
            // Admin Footer File
            $adminFooter = AdminFooter::getInstance();
            $adminFooter->write($module, 'footer.php');
            $ret[] = $adminFooter->render();
            // Templates Admin About File
            $adminTemplatesAbout = TemplatesAdminAbout::getInstance();
            $adminTemplatesAbout->write($module, $moduleDirname . '_admin_about.tpl');
            $ret[] = $adminTemplatesAbout->render();
            // Templates Admin Index File
            $adminTemplatesIndex = TemplatesAdminIndex::getInstance();
            $adminTemplatesIndex->write($module, $moduleDirname . '_admin_index.tpl');
            $ret[] = $adminTemplatesIndex->render();
            // Templates Admin Footer File
            $adminTemplatesFooter = TemplatesAdminFooter::getInstance();
            $adminTemplatesFooter->write($module, $moduleDirname . '_admin_footer.tpl');
            $ret[] = $adminTemplatesFooter->render();
            // Templates Admin Header File
            $adminTemplatesHeader = TemplatesAdminHeader::getInstance();
            $adminTemplatesHeader->write($module, $moduleDirname . '_admin_header.tpl');
            $ret[] = $adminTemplatesHeader->render();
            // Language Admin File
            $languageAdmin = LanguageAdmin::getInstance();
            $languageAdmin->write($module, $table, $tables, 'admin.php');
            $ret[] = $languageAdmin->render();
        }
        // Class Helper File
        $classHelper = ClassHelper::getInstance();
        $classHelper->write($module, 'helper.php');
        $ret[] = $classHelper->render();
        // Include Functions File
        $includeFunctions = IncludeFunctions::getInstance();
        $includeFunctions->write($module, $table, 'functions.php');
        $ret[] = $includeFunctions->render();
        // Creation of blocks language file
        if ($table->getVar('table_name') != null) {
            // Include Install File
            $includeInstall = IncludeInstall::getInstance();
            $includeInstall->write($module, $table, $tables, 'install.php');
            $ret[] = $includeInstall->render();
            if (1 == $table->getVar('table_blocks')) {
                // Language Blocks File
                $languageBlocks = LanguageBlocks::getInstance();
                $languageBlocks->write($module, $tables, 'blocks.php');
                $ret[] = $languageBlocks->render();
            }
            // Creation of admin permission files
			if (1 == $table->getVar('table_permissions')) {
				// Admin Permissions File
				$adminPermissions = AdminPermissions::getInstance();
				$adminPermissions->write($module, $table, $tables, 'permissions.php');
				$ret[] = $adminPermissions->render();
				// Templates Admin Permissions File
				$adminTemplatesPermissions = TemplatesAdminPermissions::getInstance();
				$adminTemplatesPermissions->write($module, $moduleDirname . '_admin_permissions.tpl');
				$ret[] = $adminTemplatesPermissions->render();
			}
            // Creation of notifications files
            if (1 == $table->getVar('table_notifications')) {
                // Include Notifications File
                $includeNotifications = IncludeNotifications::getInstance();
                $includeNotifications->write($module, $table, 'notifications.inc.php');
                $ret[] = $includeNotifications->render();
                // Language Mail Template Category File
                $languageMailTpl = LanguageMailTpl::getInstance();
                $languageMailTpl->write($module);
                $ret[] = $languageMailTpl->renderFile('category_new_notify.tpl');
            }
            // Creation of sql file
            if ($table->getVar('table_name') != null) {
                // Sql File
                $sqlFile = SqlFile::getInstance();
                $sqlFile->write($module, $tables, 'mysql.sql');
                $ret[] = $sqlFile->render();
                // Include Update File
                $includeUpdate = IncludeUpdate::getInstance();
                $includeUpdate->write($module, 'update.php');
                $ret[] = $includeUpdate->renderFile();
            }
            // Creation of search file
            if (1 == $table->getVar('table_search')) {
                // Include Search File
                $includeSearch = IncludeSearch::getInstance();
                $includeSearch->write($module, $table, 'search.inc.php');
                $ret[] = $includeSearch->render();
            }
            // Creation of comments files
            if (1 == $table->getVar('table_comments')) {
                // Include Comments File
                $includeComments = IncludeComments::getInstance();
                $includeComments->write($module, $table);
                $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_edit');
                // Include Comments File
                $includeComments = IncludeComments::getInstance();
                $includeComments->write($module, $table);
                $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_delete');
                // Include Comments File
                $includeComments = IncludeComments::getInstance();
                $includeComments->write($module, $table);
                $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_post');
                // Include Comments File
                $includeComments = IncludeComments::getInstance();
                $includeComments->write($module, $table);
                $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_reply');
                // Include Comments File
                $includeComments = IncludeComments::getInstance();
                $includeComments->write($module, $table);
                $ret[] = $includeComments->renderCommentsNew($module, 'comment_new');
                // Include Comment Functions File
                $includeCommentFunctions = IncludeCommentFunctions::getInstance();
                $includeCommentFunctions->write($module, $table, 'comment_functions.php');
                $ret[] = $includeCommentFunctions->renderFile();
            }        
		}
        // Creation of admin files
        if (1 == $module->getVar('mod_admin')) {
            // Templates Index File
            $userTemplatesIndex = TemplatesUserIndex::getInstance();
            $userTemplatesIndex->write($module, $table, $moduleDirname . '_index.tpl');
            $ret[] = $userTemplatesIndex->render();
            // Templates Footer File
            $userTemplatesFooter = TemplatesUserFooter::getInstance();
            $userTemplatesFooter->write($module, $table, $moduleDirname . '_footer.tpl');
            $ret[] = $userTemplatesFooter->render();
            // Templates Header File
            $userTemplatesHeader = TemplatesUserHeader::getInstance();
            $userTemplatesHeader->write($module, $table, $tables, $moduleDirname . '_header.tpl');
            $ret[] = $userTemplatesHeader->render();
        }
        // Creation of user files
        if ((1 == $module->getVar('mod_user')) && (1 == $table->getVar('table_user'))) {
            // User Footer File
            $userFooter = UserFooter::getInstance();
            $userFooter->write($module, 'footer.php');
            $ret[] = $userFooter->render();
            // User Header File
            $userHeader = UserHeader::getInstance();
            $userHeader->write($module, $table, $tables, 'header.php');
            $ret[] = $userHeader->render();
            // User Notification Update File
            if ((1 == $module->getVar('mod_notifications')) && (1 == $table->getVar('table_notifications'))) {
                $userNotificationUpdate = UserNotificationUpdate::getInstance();
                $userNotificationUpdate->write($module, 'notification_update.php');
                $ret[] = $userNotificationUpdate->render();
            }
			// User Broken File
            if ((1 == $table->getVar('table_broken'))) {
                $userBroken = UserBroken::getInstance();
                $userBroken->write($module, $table, 'broken.php');
                $ret[] = $userBroken->render();
				// User Templates Broken File
                $userTemplatesBroken = TemplatesUserBroken::getInstance();
                $userTemplatesBroken->write($module, $table);
                $ret[] = $userTemplatesBroken->renderFile($moduleDirname . '_broken.tpl');
            }
			// User Pdf File
            if ((1 == $table->getVar('table_pdf'))) {
                $userPdf = UserPrint::getInstance();
                $userPdf->write($module, $table, 'pdf.php');
                $ret[] = $userPdf->render();
				// User Templates Pdf File
                $userTemplatesPdf = TemplatesUserPdf::getInstance();
                $userTemplatesPdf->write($module, $table);
                $ret[] = $userTemplatesPdf->renderFile($moduleDirname . '_pdf.tpl');
            }
			// User Print File
            if ((1 == $table->getVar('table_print'))) {
                $userPrint = UserPrint::getInstance();
                $userPrint->write($module, $table, 'print.php');
                $ret[] = $userPrint->render();
				// User Templates Print File
                $userTemplatesPrint = TemplatesUserPrint::getInstance();
                $userTemplatesPrint->write($module, $table);
                $ret[] = $userTemplatesPrint->renderFile($moduleDirname . '_print.tpl');
            }
			// User Rate File
            if ((1 == $table->getVar('table_rate'))) {
                $userRate = UserRate::getInstance();
                $userRate->write($module, $table, 'rate.php');
                $ret[] = $userRate->render();
				// User Templates Rate File
                $userTemplatesRate = TemplatesUserRate::getInstance();
                $userTemplatesRate->write($module, $table);
                $ret[] = $userTemplatesRate->renderFile($moduleDirname . '_rate.tpl');
            }
			// User Rss File
            if ((1 == $table->getVar('table_rss'))) {
                $userRss = UserRss::getInstance();
                $userRss->write($module, $table, 'rss.php');
                $ret[] = $userRss->render();
				// User Templates Rss File
                $userTemplatesRss = TemplatesUserRss::getInstance();
                $userTemplatesRss->write($module);
                $ret[] = $userTemplatesRss->renderFile($moduleDirname . '_rss.tpl');
            }
			// User Single File
            if ((1 == $table->getVar('table_single'))) {
                $userSingle = UserSingle::getInstance();
                $userSingle->write($module, $table, 'single.php');
                $ret[] = $userSingle->render();
				// User Templates Single File
                $userTemplatesSingle = TemplatesUserSingle::getInstance();
                $userTemplatesSingle->write($module, $table);
                $ret[] = $userTemplatesSingle->renderFile($moduleDirname . '_single.tpl');
            }
			// User Submit File
            if ((1 == $table->getVar('table_submit'))) {
                $userSubmit = UserSubmit::getInstance();
                $userSubmit->write($module, $table, 'submit.php');
                $ret[] = $userSubmit->render();
				// User Templates Submit File
                $userTemplatesSubmit = TemplatesUserSubmit::getInstance();
                $userTemplatesSubmit->write($module, $table);
                $ret[] = $userTemplatesSubmit->renderFile($moduleDirname . '_submit.tpl');
            }// User Visit File
            if ((1 == $table->getVar('table_visit'))) {
                $userVisit = UserVisit::getInstance();
                $userVisit->write($module, $table, 'visit.php');
                $ret[] = $userVisit->render();
            }
			// User Tag Files
            if ((1 == $table->getVar('table_tag'))) {
                $userListTag = UserListTag::getInstance();
                $userListTag->write($module, 'list.tag.php');
                $ret[] = $userListTag->render();
				$userViewTag = UserViewTag::getInstance();
                $userViewTag->write($module, 'view.tag.php');
                $ret[] = $userViewTag->render();
            }
            // User Index File
            $userIndex = UserIndex::getInstance();
            $userIndex->write($module, $table, 'index.php');
            $ret[] = $userIndex->render();
            // Language Main File
            $languageMain = LanguageMain::getInstance();
            $languageMain->write($module, $tables, 'main.php');
            $ret[] = $languageMain->render();
			// User Templates Submit File
			$userTemplatesUserBreadcrumbs = TemplatesUserBreadcrumbs::getInstance();
			$userTemplatesUserBreadcrumbs->write($module, $moduleDirname . '_breadcrumbs.tpl');
			$ret[] = $userTemplatesUserBreadcrumbs->render();
        }
        // Css Styles File
        $cssStyles = CssStyles::getInstance();
        $cssStyles->write($module, 'style.css');
        $ret[] = $cssStyles->render();
        // Include Common File
        $includeCommon = IncludeCommon::getInstance();
        $includeCommon->write($module, $table, 'common.php');
        $ret[] = $includeCommon->render();
        // Docs Changelog File
        $docsChangelog = DocsChangelog::getInstance();
        $docsChangelog->write($module, 'changelog.txt');
        $ret[] = $docsChangelog->render();
        // Language Help File
        $languageHelp = LanguageHelp::getInstance();
        $languageHelp->write($module, 'help.html');
        $ret[] = $languageHelp->render();
        // Include Jquery File
        $includeJquery = IncludeJquery::getInstance();
        $includeJquery->write($module, 'functions.js');
        $ret[] = $includeJquery->render();
        // User Xoops Version File
        $userXoopsVersion = UserXoopsVersion::getInstance();
        $userXoopsVersion->write($module, $table, $tables, 'xoops_version.php');
        $ret[] = $userXoopsVersion->render();

        // Return Array
        return $ret;
    }
}
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
 * @version         $Id: UserRss.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserRss.
 */
class UserRss extends TDMCreateFile
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
     * @return UserRss
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
    *  @public function getUserRss
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    public function getUserRss($moduleDirname, $language)
    {
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $rpFieldName = $fieldName;
            if (strpos($fieldName, '_')) {
                $str = strpos($fieldName, '_');
                if ($str !== false) {
                    $rpFieldName = substr($fieldName, $str + 1, strlen($fieldName));
                }
            }
            $lpFieldName = substr($fieldName, 0, strpos($fieldName, '_'));
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fpmf = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName;
            } else {
                $fieldParent = 'cid';
            }
        }

        $ret = <<<EOT
include  __DIR__ . '/header.php';
\${$fieldParent} = {$moduleDirname}_CleanVars(\$_GET, '{$fieldParent}', 0);
include_once XOOPS_ROOT_PATH.'/class/template.php';
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
//header ('Content-Type:text/xml; charset=UTF-8');
\${$moduleDirname}->geConfig("utf8") = false;

\$tpl = new XoopsTpl();
\$tpl->xoops_setCaching(2); //1 = Cache global, 2 = Cache individual (for template)
\$tpl->xoops_setCacheTime(\${$moduleDirname}->geConfig('timecacherss')*60); // Time of the cache on seconds
\$categories = {$moduleDirname}_MygetItemIds('{$moduleDirname}_view', '{$moduleDirname}');
\$criteria = new CriteriaCompo();
\$criteria->add(new Criteria('cat_status', 0, '!='));
\$criteria->add(new Criteria('{$fieldParent}', '(' . implode(',', \$categories) . ')','IN'));
if (\${$fieldParent} != 0){
    \$criteria->add(new Criteria('{$fieldParent}', \${$fieldParent}));
    \${$tableName} = \${$tableName}Handler->get(\${$fieldParent});
    \$title = \$xoopsConfig['sitename'] . ' - ' . \$xoopsModule->getVar('name') . ' - ' . \${$tableName}->getVar('{$fpmf}');
}else{
    \$title = \$xoopsConfig['sitename'] . ' - ' . \$xoopsModule->getVar('name');
}
\$criteria->setLimit(\${$moduleDirname}->geConfig('perpagerss'));
\$criteria->setSort('date');
\$criteria->setOrder('DESC');
\${$tableName}Arr = \${$tableName}Handler->getAll(\$criteria);
unset(\$criteria);

if (!\$tpl->is_cached('db:{$moduleDirname}_rss.tpl', \${$fieldParent})) {
    \$tpl->assign('channel_title', htmlspecialchars(\$title, ENT_QUOTES));
    \$tpl->assign('channel_link', XOOPS_URL.'/');
    \$tpl->assign('channel_desc', htmlspecialchars(\$xoopsConfig['slogan'], ENT_QUOTES));
    \$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    \$tpl->assign('channel_webmaster', \$xoopsConfig['adminmail']);
    \$tpl->assign('channel_editor', \$xoopsConfig['adminmail']);
    \$tpl->assign('channel_category', 'Event');
    \$tpl->assign('channel_generator', 'XOOPS - ' . htmlspecialchars(\$xoopsModule->getVar('{$fpmf}'), ENT_QUOTES));
    \$tpl->assign('channel_language', _LANGCODE);
    if ( _LANGCODE == 'fr' ) {
        \$tpl->assign('docs', 'http://www.scriptol.fr/rss/RSS-2.0.html');
    } else {
        \$tpl->assign('docs', 'http://cyber.law.harvard.edu/rss/rss.html');
    }
    \$tpl->assign('image_url', XOOPS_URL . \$xoopsModuleConfig['logorss']);
    \$dimention = getimagesize(XOOPS_ROOT_PATH . \$xoopsModuleConfig['logorss']);
    if (empty(\$dimention[0])) {
        \$width = 88;
    } else {
       \$width = (\$dimention[0] > 144) ? 144 : \$dimention[0];
    }
    if (empty(\$dimention[1])) {
        \$height = 31;
    } else {
        \$height = (\$dimention[1] > 400) ? 400 : \$dimention[1];
    }
    \$tpl->assign('image_width', \$width);
    \$tpl->assign('image_height', \$height);
    foreach (array_keys(\${$tableName}Arr) as \$i) {
        \$description = \${$tableName}Arr[\$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (strpos(\$description,'[pagebreak]')==false){
            \$description_short = \$description;
        }else{
            \$description_short = substr(\$description,0,strpos(\$description,'[pagebreak]'));
        }
        \$tpl->append('items', array('title' => htmlspecialchars(\${$tableName}Arr[\$i]->getVar('{$fpmf}'), ENT_QUOTES),
                                    'link' => XOOPS_URL . '/modules/{$moduleDirname}/single.php?{$fieldParent}=' . \${$tableName}Arr[\$i]->getVar('{$fieldParent}') . '&amp;{$fieldId}=' . \${$tableName}Arr[\$i]->getVar('{$fieldId}'),
                                    'guid' => XOOPS_URL . '/modules/{$moduleDirname}/single.php?{$fieldParent}=' . \${$tableName}Arr[\$i]->getVar('{$fieldParent}') . '&amp;{$fieldId}=' . \${$tableName}Arr[\$i]->getVar('{$fieldId}'),
                                    'pubdate' => formatTimestamp(\${$tableName}Arr[\$i]->getVar('date'), 'rss'),
                                    'description' => htmlspecialchars(\$description_short, ENT_QUOTES)));
    }
}
header("Content-Type:text/xml; charset=" . _CHARSET);
\$tpl->display('db:{$moduleDirname}_rss.tpl', \${$fieldParent});
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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserRss($moduleDirname, $language);
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

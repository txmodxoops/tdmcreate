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
 * @version         $Id: TemplatesAdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TemplatesAdminPages
 */
class TemplatesAdminPages extends TDMCreateHtmlSmartyCodes
{
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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesAdminPages
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
    *  @param string $table
    */
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getTemplatesAdminPagesHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesHeader($moduleDirname, $table, $fields, $language)
    {
        $tableName     = $table->getVar('table_name');
		$tableSoleName = $table->getVar('table_solename');
        $ret           = <<<EOT
<{include file="db:{$moduleDirname}_admin_header.tpl"}>
<{if {$tableName}_list}>
	<table class="outer {$tableName} width100">
        <thead>
            <tr class="head">\n
EOT;
        $langHeadId = $language . strtoupper($tableSoleName) . '_ID';
		if (1 == $table->getVar('table_autoincrement')) {
			$ret .= <<<EOT
				<th class="center"><{\$smarty.const.{$langHeadId}}></th>\n
EOT;
		}
		foreach (array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
			$rpFieldName   = $this->tdmcfile->getRightString($fieldName);
            $langFieldName = $language . strtoupper($tableSoleName) . '_' . strtoupper($rpFieldName);
			if (1 == $fields[$f]->getVar('field_inlist')) {
				$ret .= <<<EOT
                <th class="center"><{\$smarty.const.{$langFieldName}}></th>\n
EOT;
			}
        }
        $ret .= <<<EOT
                <th class="center"><{\$smarty.const.{$language}FORM_ACTION}></th>
            </tr>
        </thead>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesAdminPagesBody
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesBody($moduleDirname, $table, $fields, $language)
    {
        $tableName = $table->getVar('table_name');
        $ret       = <<<EOT
        <{if {$tableName}_count}>
		<tbody>		
            <{foreach item=list from=\${$tableName}_list}>
                <tr class="<{cycle values='odd, even'}>">\n
EOT;
        if (1 == $table->getVar('table_autoincrement')) {
			$ret .= <<<EOT
					<td class="center"><{\$list.id}></td>\n
EOT;
		}
		foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->tdmcfile->getRightString($fieldName);
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_inlist')) {
                switch ($fieldElement) {
                    case 9:
                        $ret .= <<<EOT
                    <td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n
EOT;
                        break;
                    case 10:
                        $ret .= <<<EOT
                    <td class="center"><img src="<{xoModuleIcons32}><{\$list.{$rpFieldName}}>" alt="{$tableName}"></td>\n
EOT;
                        break;
                    case 13:
                        $ret .= <<<EOT
                    <td class="center"><img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$rpFieldName}}>" alt="{$tableName}"></td>\n
EOT;
                        break;
                    default:
                        $ret .= <<<EOT
                    <td class="center"><{\$list.{$rpFieldName}}></td>\n
EOT;
                        break;
                }
            }
        }
        $ret .= <<<EOT
                    <td class="center">
                        <a href="{$tableName}.php?op=edit&amp;{$fieldId}=<{\$list.id}>" title="<{\$smarty.const._EDIT}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{\$smarty.const._EDIT}>" />
                        </a>
                        <a href="{$tableName}.php?op=delete&amp;{$fieldId}=<{\$list.id}>" title="<{\$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{\$smarty.const._DELETE}>" />
                        </a>
                    </td>
                </tr>
            <{/foreach}>		
        </tbody>
		<{/if}>
    </table>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesAdminPagesBodyFieldnameEmpty
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesBodyFieldnameEmpty($moduleDirname, $table, $fields, $language)
    {
        $tableName = $table->getVar('table_name');
        $ret       = <<<EOT
		<{if {$tableName}_count}>
		<tbody>		
            <{foreach item=list from=\${$tableName}_list}>
                <tr class="<{cycle values='odd, even'}>">\n
EOT;
        if (1 == $table->getVar('table_autoincrement')) {
			$ret .= <<<EOT
					<td class="center"><{\$list.id}></td>\n
EOT;
		}
		foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_inlist')) {
                switch ($fieldElement) {
                    case 9:
                        $ret .= <<<EOT
                    <td class="center"><span style="background-color: #<{\$list.{$fieldName}}>;">\t\t</span></td>\n
EOT;
                        break;
                    case 10:
                        $ret .= <<<EOT
                    <td class="center"><img src="<{xoModuleIcons32}><{\$list.{$fieldName}}>" alt="{$tableName}"></td>\n
EOT;
                        break;
                    case 13:
                        $ret .= <<<EOT
                    <td class="center"><img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$fieldName}}>" alt="{$tableName}"></td>\n
EOT;
                        break;
                    default:
                        $ret .= <<<EOT
                    <td class="center"><{\$list.{$fieldName}}></td>\n
EOT;
                        break;
                }
            }
        }
        $ret .= <<<EOT
                    <td class="center">
                        <a href="{$tableName}.php?op=edit&amp;{$fieldId}=<{\$list.{$fieldId}}>" title="<{\$smarty.const._EDIT}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{\$smarty.const._EDIT}>" />
                        </a>
                        <a href="{$tableName}.php?op=delete&amp;{$fieldId}=<{\$list.{$fieldId}}>" title="<{\$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{\$smarty.const._DELETE}>" />
                        </a>
                    </td>
                </tr>
            <{/foreach}>		
        </tbody>
		<{/if}>
    </table>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesAdminPagesFooter
    *  @param string $moduleDirname
    *  @return string
    */
    private function getTemplatesAdminPagesFooter($moduleDirname)
    {
        $ret = <<<EOT
    <div class="clear">&nbsp;</div>
    <{if \$pagenav}><br />
        <!-- Display navigation -->
        <div class="xo-pagenav floatright"><{\$pagenav}></div><div class="clear spacer"></div>
    <{/if}>
<{/if}>
<{if \$form}>
	<!-- Display form (add,edit) -->
	<div class="spacer"><{\$form}></div>
<{/if}>
<{if \$error}>
    <div class="errorMsg">
        <strong><{\$error}></strong>
    </div>
<{/if}>
<br />
<!-- Footer -->
<{include file="db:{$moduleDirname}_admin_footer.tpl"}>
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param $filename
    *  @return bool|string
    */
    public function renderFile($filename)
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'AM');
        $fields         = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $content        = $this->getTemplatesAdminPagesHeader($moduleDirname, $table, $fields, $language);
        // Verify if table_fieldname is not empty
        if (!empty($tableFieldname)) {
            $content .= $this->getTemplatesAdminPagesBody($moduleDirname, $table, $fields, $language);
        } else {
            $content .= $this->getTemplatesAdminPagesBodyFieldnameEmpty($moduleDirname, $table, $fields, $language);
        }
        $content .= $this->getTemplatesAdminPagesFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

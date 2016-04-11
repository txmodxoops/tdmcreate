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
 * @version         $Id: TemplatesUserRss.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserRss.
 */
class TemplatesUserRss extends TDMCreateFile
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
	}

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserRss
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
    *  @param $module
    *  @param $filename
    */
    /**
     * @param $module
     * @param $table
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
		$this->setFileName($filename);
    }

    /*
    *  @private function getTemplatesUserRssXml
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserRssXml()
    {
        $ret = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title><{\$channel_title}></title>
    <link><{\$channel_link}></link>
    <description><{\$channel_desc}></description>
    <lastBuildDate><{\$channel_lastbuild}></lastBuildDate>
    <docs>http://backend.userland.com/rss/</docs>
    <generator><{\$channel_generator}></generator>
    <category><{\$channel_category}></category>
    <managingEditor><{\$channel_editor}></managingEditor>
    <webMaster><{\$channel_webmaster}></webMaster>
    <language><{\$channel_language}></language>
    <{if \$image_url != ""}>
    <image>
      <title><{\$channel_title}></title>
      <url><{\$image_url}></url>
      <link><{\$channel_link}></link>
      <width><{\$image_width}></width>
      <height><{\$image_height}></height>
    </image>
    <{/if}>
    <{foreach item=item from=\$items}>
    <item>
      <title><{\$item.title}></title>
      <link><{\$item.link}></link>
      <description><{\$item.description}></description>
      <pubDate><{\$item.pubdate}></pubDate>
      <guid><{\$item.guid}></guid>
    </item>
    <{/foreach}>
  </channel>
</rss>\n
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param null
    */
    /**
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
		$filename = $this->getFileName();
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserRssXml();
        //
        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

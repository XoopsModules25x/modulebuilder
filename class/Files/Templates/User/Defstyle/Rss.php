<?php

namespace XoopsModules\Modulebuilder\Files\Templates\User\Defstyle;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * class Rss.
 */
class Rss extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return Rss
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
     * @public   function write
     * @param        $module
     * @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserRssXml
     * @param null
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

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getTemplatesUserRssXml();

        $this->create($moduleDirname, 'templates', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

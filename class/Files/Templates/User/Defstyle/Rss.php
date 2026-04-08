<?php declare(strict_types=1);

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
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * class Rss.
 */
class Rss extends Files\CreateFile
{
    /**
     * @public function constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
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
    public function write($module, string $filename): void
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserRssXml
     * @return string
     */
    private function getTemplatesUserRssXml()
    {
        return <<<EOT
            <?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
              <channel>
                <title><{\$channel_title|escape:'html':'UTF-8'}></title>
                <link><{\$channel_link|escape:'html':'UTF-8'}></link>
                <description><{\$channel_desc|escape:'html':'UTF-8'}></description>
                <lastBuildDate><{\$channel_lastbuild|escape:'html':'UTF-8'}></lastBuildDate>
                <docs><{\$docs|escape:'html':'UTF-8'}></docs>
                <generator><{\$channel_generator|escape:'html':'UTF-8'}></generator>
                <category><{\$channel_category|escape:'html':'UTF-8'}></category>
                <managingEditor><{\$channel_editor|escape:'html':'UTF-8'}></managingEditor>
                <webMaster><{\$channel_webmaster|escape:'html':'UTF-8'}></webMaster>
                <language><{\$channel_language|escape:'html':'UTF-8'}></language>
                <{if \$image_url != ""}>
                <image>
                  <title><{\$channel_title|escape:'html':'UTF-8'}></title>
                  <url><{\$image_url|escape:'html':'UTF-8'}></url>
                  <link><{\$channel_link|escape:'html':'UTF-8'}></link>
                  <width><{\$image_width}></width>
                  <height><{\$image_height}></height>
                </image>
                <{/if}>
                <{foreach item=item from=\$items}>
                <item>
                  <title><{\$item.title|escape:'html':'UTF-8'}></title>
                  <link><{\$item.link|escape:'html':'UTF-8'}></link>
                  <description><{\$item.description|escape:'html':'UTF-8'}></description>
                  <pubDate><{\$item.pubdate|escape:'html':'UTF-8'}></pubDate>
                  <guid><{\$item.guid|escape:'html':'UTF-8'}></guid>
                </item>
                <{/foreach}>
              </channel>
            </rss>\n
            EOT;
    }

    /**
     * @public function render
     * @return string
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

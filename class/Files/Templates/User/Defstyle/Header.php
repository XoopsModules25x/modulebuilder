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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * class Header.
 */
class Header extends Files\CreateFile
{
    /**
     * @var string
     */
    private $tdmcfile = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = Modulebuilder\Files\CreateFile::getInstance();
        $this->htmlcode = Modulebuilder\Files\CreateHtmlCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return Header
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
     * @public function write
     * @param        $module
     * @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @public function getTemplatesUserHeader
     * @param $moduleDirname
     * @return bool|string
     */
    public function getTemplatesUserHeader($moduleDirname)
    {
        $hc  = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc  = Modulebuilder\Files\CreateSmartyCode::getInstance();
		$ret = $sc->getSmartyIncludeFile($moduleDirname, 'breadcrumbs', false, true, '', "\n\n");
        $var = $sc->getSmartySingleVar('ads', '', '');
        $div = $hc->getHtmlDiv($var, 'center', "\t","\n", false) ;
        $ret .= $sc->getSmartyConditions('ads', ' != ', '\'\'', $div);

        return $ret;
    }

    /**
     * @public function getTemplateFooterFacebbokSDK
     * @param null
     *
     * @return bool|string
     */
    public function getTemplateUserHeaderFacebbokSDK()
    {
        $ret = <<<'EOT'
	<!-- Load Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
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
        //$language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserHeader($moduleDirname);

        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}

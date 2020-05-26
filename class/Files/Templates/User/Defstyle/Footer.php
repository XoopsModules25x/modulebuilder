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
 * class Footer.
 */
class Footer extends Files\CreateFile
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
    }

    /**
     * @static function getInstance
     * @param null
     * @return Footer
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
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getTemplateUserFooterFacebookLikeButton
     * @param null
     *
     * @return bool|string
     */
    public function getTemplateUserFooterFacebookLikeButton()
    {
        return "<li class='fb-like' data-href='<{\$xoops_mpageurl}>' data-layout='standard' data-action='like' data-show-faces='true'></li>";
    }

    /**
     * @public function getTemplateUserFooterFacebookShareButton
     * @param null
     *
     * @return bool|string
     */
    public function getTemplateUserFooterFacebookShareButton()
    {
        return "<li class='fb-share-button' data-href='<{\$xoops_mpageurl}>' data-layout='button_count'></li>";
    }

    /**
     * @public function render
     * @param $language
     * @return bool|string
     */
    private function getTemplateUserFooterContent($language)
    {
        $hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc = Modulebuilder\Files\CreateSmartyCode::getInstance();
        $ret     = $hc->getHtmlDiv('<{$copyright}>', 'pull-left', '', "\n", false);
        $ret     .= $hc->getHtmlEmpty("\n");
        $contIf  = $hc->getHtmlDiv('<{$pagenav}>', 'pull-right', "\t", "\n", false);
        $ret     .= $sc->getSmartyConditions('pagenav', ' != ', "''", $contIf);
        $ret     .= $hc->getHtmlEmpty("<br>\n");
        $contIf  = $hc->getHtmlDiv("<a href='<{\$admin}>'><{\$smarty.const.{$language}ADMIN}></a>", 'text-center bold', "\t", "\n", false);
        $ret     .= $sc->getSmartyConditions('xoops_isadmin', ' != ', "''", $contIf);
        $ret     .= $hc->getHtmlEmpty("\n");
        $contIf  = $sc->getSmartyIncludeFile('system_comments','flat',false, false,"\t\t\t");
        $contIf  .= $this->getSimpleString('<{elseif $comment_mode == "thread"}>',"\t\t");
        $contIf  .= $sc->getSmartyIncludeFile('system_comments','thread',false, false,"\t\t\t");
        $contIf  .= $this->getSimpleString('<{elseif $comment_mode == "nest"}>',"\t\t");
        $contIf  .= $sc->getSmartyIncludeFile('system_comments','nest',false, false,"\t\t\t");
        $contDiv = $sc->getSmartyConditions('comment_mode', ' == ', '"flat"', $contIf, false, '','',"\t\t");
        $contIf  = $hc->getHtmlDiv($contDiv, 'pad2 marg2', "\t", "\n", true);
        $ret     .= $sc->getSmartyConditions('comment_mode', '', '', $contIf);
        $ret     .= $sc->getSmartyIncludeFile('system_notification','select');

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
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplateUserFooterContent($language);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

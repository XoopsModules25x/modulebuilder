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
 * class Submit.
 */
class Submit extends Files\CreateFile
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
     * @return Submit
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
     * @param string $module
     * @param string $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserSubmitHeader
     * @param $moduleDirname
     * @return string
     */
    private function getTemplatesUserSubmitHeader($moduleDirname)
    {
        $sc  = Modulebuilder\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'header');
    }

    /**
     * @private function getTemplatesUserSubmit
     * @param string $moduleDirname
     * @param string $language
     *
     * @return string
     */
    private function getTemplatesUserSubmit($moduleDirname, $language)
    {
        $hc    = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc    = Modulebuilder\Files\CreateSmartyCode::getInstance();

        $const = $sc->getSmartyConst($language, 'SUBMIT_SUBMITONCE');
        $li    = $hc->getHtmlLi($const, '',"\t\t");
        $const = $sc->getSmartyConst($language, 'SUBMIT_ALLPENDING');
        $li    .= $hc->getHtmlLi($const, '',"\t\t");
        $const = $sc->getSmartyConst($language, 'SUBMIT_DONTABUSE');
        $li    .= $hc->getHtmlLi($const, '',"\t\t");
        $const = $sc->getSmartyConst($language, 'SUBMIT_TAKEDAYS');
        $li    .= $hc->getHtmlLi($const, '',"\t\t");
        $ul    = $hc->getHtmlUl($li, '', "\t");

        $ret      = $hc->getHtmlEmpty('', '',"\n");
        $ret     .= $hc->getHtmlDiv($ul, $moduleDirname . '-tips');
        $single   = $sc->getSmartySingleVar('message_error');
        $divError = $hc->getHtmlDiv($single, 'errorMsg', "\t", "\n", false);
        $ret      .= $sc->getSmartyConditions('message_error', ' != ', '\'\'', $divError);
        $single   = $sc->getSmartySingleVar('form', "\t", "\n");
        $ret      .= $hc->getHtmlDiv($single, $moduleDirname . '-submitform');
        $ret      .= $hc->getHtmlEmpty('', '',"\n");

        return $ret;
    }

    /**
     * @private function getTemplatesUserSubmitFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserSubmitFooter($moduleDirname)
    {
        $sc  = Modulebuilder\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module         = $this->getModule();
        //$table          = $this->getTable();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        //$tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MA');
        $content        = $this->getTemplatesUserSubmitHeader($moduleDirname);
        $content        .= $this->getTemplatesUserSubmit($moduleDirname, $language);
        $content        .= $this->getTemplatesUserSubmitFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

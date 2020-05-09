<?php

namespace XoopsModules\Modulebuilder\Files\User;

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
 * Class UserFooter.
 */
class UserFooter extends Files\CreateFile
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
     * @return UserFooter
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
     * @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserFooter
     * @param $moduleDirname
     *
     * @return string
     */
    private function getUserFooter($moduleDirname)
    {
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $xoBreadcrumbs    = $xc->getXcXoopsTplAssign('xoBreadcrumbs', '$xoBreadcrumbs', true, "\t");
        $ret              = $pc->getPhpCodeConditions('count($xoBreadcrumbs)', ' > ', '1', $xoBreadcrumbs);
        $ret              .= $xc->getXcXoopsTplAssign('adv', "\$helper->getConfig('advertise')");
        $ret              .= $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcXoopsTplAssign('bookmarks', "\$helper->getConfig('bookmarks')");
        $ret              .= $xc->getXcXoopsTplAssign('fbcomments', "\$helper->getConfig('fbcomments')");
        $ret              .= $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcXoopsTplAssign('admin', "{$stuModuleDirname}_ADMIN");
        $ret              .= $xc->getXcXoopsTplAssign('copyright', '$copyright');
        $ret              .= $pc->getPhpCodeCommentLine();
        $ret              .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'footer', true);

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserFooter($moduleDirname);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

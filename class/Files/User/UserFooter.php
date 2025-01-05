<?php declare(strict_types=1);

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * Class UserFooter.
 */
class UserFooter extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $uxc = null;
    /**
     * @var mixed
     */
    private $xc = null;
    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = Modulebuilder\Files\User\UserXoopsCode::getInstance();
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
    public function write($module, $filename): void
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserFooter
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getUserFooter($moduleDirname, $language)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $xoBreadcrumbs    = $this->xc->getXcXoopsTplAssign('xoBreadcrumbs', '$xoBreadcrumbs', true, "\t");
        $config           = $this->xc->getXcGetConfig('show_breadcrumbs');
        $ret              = $this->pc->getPhpCodeConditions($config . ' && \count($xoBreadcrumbs) > 0', '', '', $xoBreadcrumbs);
        $ret              .= $this->xc->getXcXoopsTplAssign('adv', "\$helper->getConfig('advertise')");
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->xc->getXcXoopsTplAssign('bookmarks', "\$helper->getConfig('bookmarks')");
        $ret              .= $this->xc->getXcXoopsTplAssign('fbcomments', "\$helper->getConfig('fbcomments')");
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->xc->getXcXoopsTplAssign('admin', "\\{$stuModuleDirname}_ADMIN");
        $ret              .= $this->xc->getXcXoopsTplAssign('copyright', '$copyright');
        $ret              .= $this->pc->getPhpCodeCommentLine('Meta description');
        $ret              .= $this->uxc->getUserMetaDesc($moduleDirname, $language);
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->pc->getPhpCodeCommentLine('Paths');
        $ret              .= $this->xc->getXcXoopsTplAssign('xoops_mpageurl', "\\{$stuModuleDirname}_URL.'/index.php'");
        $ret              .= $this->xc->getXcXoopsTplAssign('xoops_icons32_url', '\XOOPS_ICONS32_URL');
        $ret              .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_url", "\\{$stuModuleDirname}_URL");
        $ret              .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "\\{$stuModuleDirname}_UPLOAD_URL");
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'footer', true);

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
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

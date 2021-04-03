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
 * Class UserHeader.
 */
class UserHeader extends Files\CreateFile
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
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = Modulebuilder\Files\User\UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserHeader
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
     * @param mixed  $table
     * @param array  $tables
     * @param string $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserHeader
     * @param $moduleDirname
     *
     * @return string
     */
    private function getUserHeader($moduleDirname)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $tables           = $this->getTables();

        $ret = $this->pc->getPhpCodeIncludeDir('\dirname(\dirname(__DIR__))', 'mainfile');
        $ret .= $this->pc->getPhpCodeIncludeDir('__DIR__', 'include/common');
        $ret .= $this->xc->getXcEqualsOperator('$moduleDirName', '\basename(__DIR__)');
        $ret .= $this->pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->pc->getPhpCodeArray('xoBreadcrumbs', null, false, '');
        $ret .= $this->xc->getXcHelperGetInstance($moduleDirname);
        $permissions = 0;
        $ratings     = 0;
        if (\is_array($tables)) {
            foreach (\array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret       .= $this->xc->getXcHandlerLine($tableName);
                if (1 == $tables[$i]->getVar('table_permissions')) {
                    $permissions = 1;
                }
                if (1 == $tables[$i]->getVar('table_rate')) {
                    $ratings = 1;
                }
            }
        }
        if (1 == $permissions) {
            $ret .= $this->xc->getXcHandlerLine('permissions');
        }
        if (1 == $ratings) {
            $ret .= $this->xc->getXcHandlerLine('ratings');
        }
        $ret .= $this->pc->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()');
        $ret .= $this->pc->getPhpCodeCommentLine('Default Css Style');
        $ret .= $this->xc->getXcEqualsOperator('$style', "{$stuModuleDirname}_URL . '/assets/css/style.css'");
                $ret .= $this->pc->getPhpCodeCommentLine('Smarty Default');
        $ret .= $this->xc->getXcXoopsModuleGetInfo('sysPathIcon16', 'sysicons16');
        $ret .= $this->xc->getXcXoopsModuleGetInfo('sysPathIcon32', 'sysicons32');
        $ret .= $this->xc->getXcXoopsModuleGetInfo('pathModuleAdmin', 'dirmoduleadmin');
        $ret .= $this->xc->getXcXoopsModuleGetInfo('modPathIcon16', 'modicons16');
        $ret .= $this->xc->getXcXoopsModuleGetInfo('modPathIcon32', 'modicons16');
        $ret .= $this->pc->getPhpCodeCommentLine('Load Languages');
        $ret .= $this->xc->getXcXoopsLoadLanguage('main');
        $ret .= $this->xc->getXcXoopsLoadLanguage('modinfo');

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
        $content       .= $this->getUserHeader($moduleDirname);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

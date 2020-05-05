<?php

namespace XoopsModules\Tdmcreate\Files\User;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uxc              = UserXoopsCode::getInstance();
        $ret              = $pc->getPhpCodeIncludeDir('dirname(dirname(__DIR__))', 'mainfile');
        $ret              .= $pc->getPhpCodeIncludeDir('__DIR__', 'include/common');
        $ret              .= $xc->getXcEqualsOperator('$moduleDirName', 'basename(__DIR__)');
        $language         = $this->getLanguage($moduleDirname, 'MA');
        $ret              .= $uxc->getUserBreadcrumbsHeaderFile($moduleDirname, $language);

        $table  = $this->getTable();
        $tables = $this->getTables();
        $ret .= $xc->getXcHelperGetInstance($moduleDirname);
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret       .= $xc->getXcHandlerLine($tableName);
            }
        }
        if (1 == $table->getVar('table_permissions')) {
            $ret       .= $xc->getXcHandlerLine('permissions');
        }
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()');
        $ret .= $pc->getPhpCodeCommentLine('Default Css Style');
        $ret .= $xc->getXcEqualsOperator('$style', "{$stuModuleDirname}_URL . '/assets/css/style.css'");
        $ret .= $pc->getPhpCodeConditions('!file_exists($style)', '', '', "\treturn false;\n");
        $ret .= $pc->getPhpCodeCommentLine('Smarty Default');
        $ret .= $xc->getXcXoopsModuleGetInfo('sysPathIcon16', 'sysicons16');
        $ret .= $xc->getXcXoopsModuleGetInfo('sysPathIcon32', 'sysicons32');
        $ret .= $xc->getXcXoopsModuleGetInfo('pathModuleAdmin', 'dirmoduleadmin');
        $ret .= $xc->getXcXoopsModuleGetInfo('modPathIcon16', 'modicons16');
        $ret .= $xc->getXcXoopsModuleGetInfo('modPathIcon32', 'modicons16');
        $ret .= $pc->getPhpCodeCommentLine('Load Languages');
        $ret .= $xc->getXcXoopsLoadLanguage('main');
        $ret .= $xc->getXcXoopsLoadLanguage('modinfo');

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

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

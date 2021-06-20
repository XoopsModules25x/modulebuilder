<?php

namespace XoopsModules\Modulebuilder\Files\Config;

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
 * Class IncludeCommon.
 */
class ConfigConfig extends Files\CreateFile
{
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
    }

    /**
     * @static function getInstance
     * @return bool|ConfigConfig
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
     * @param $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getConfigCode
     * @return string
     */
    private function getConfigCode()
    {
        $tables = $this->getTables();

        $ret    = $this->pc->getPhpCodeCommentMultiLine(['return' => 'object']);
        $ret    .= $this->getSimpleString('');
        $ret    .= $this->xc->getXcEqualsOperator('$moduleDirName ', '\basename(\dirname(__DIR__))');
        $ret    .= $this->xc->getXcEqualsOperator('$moduleDirNameUpper ', '\mb_strtoupper($moduleDirName)');

        $ret    .= $this->getSimpleString('return (object)[');
        $ret    .= $this->getSimpleString("'name'           => \mb_strtoupper(\$moduleDirName) . ' Module Configurator',", "\t");
        $ret    .= $this->getSimpleString("'paths'          => [", "\t");
        $ret    .= $this->getSimpleString("'dirname'    => \$moduleDirName,", "\t\t");
        $ret    .= $this->getSimpleString("'admin'      => \XOOPS_ROOT_PATH . '/modules/' . \$moduleDirName . '/admin',", "\t\t");
        $ret    .= $this->getSimpleString("'modPath'    => \XOOPS_ROOT_PATH . '/modules/' . \$moduleDirName,", "\t\t");
        $ret    .= $this->getSimpleString("'modUrl'     => \XOOPS_URL . '/modules/' . \$moduleDirName,", "\t\t");
        $ret    .= $this->getSimpleString("'uploadPath' => \XOOPS_UPLOAD_PATH . '/' . \$moduleDirName,", "\t\t");
        $ret    .= $this->getSimpleString("'uploadUrl'  => \XOOPS_UPLOAD_URL . '/' . \$moduleDirName,", "\t\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'uploadFolders'  => [", "\t");
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName,", "\t\t");
        foreach (\array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $ret       .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/{$tableName}',", "\t\t");
        }
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/images',", "\t\t");
        foreach (\array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $ret       .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/images/{$tableName}',", "\t\t");
        }
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/files',", "\t\t");
        foreach (\array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $ret       .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/files/{$tableName}',", "\t\t");
        }
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/temp',", "\t\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'copyBlankFiles'  => [", "\t");
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/images',", "\t\t");
        foreach (\array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $ret       .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName . '/images/{$tableName}',", "\t\t");
        }
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'copyTestFolders'  => [", "\t");
        $ret    .= $this->getSimpleString("[\XOOPS_ROOT_PATH . '/modules/' . \$moduleDirName . '/testdata/uploads',", "\t\t");
        $ret    .= $this->getSimpleString("\XOOPS_UPLOAD_PATH . '/' . \$moduleDirName],", "\t\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'templateFolders'  => [", "\t");
        $ret    .= $this->getSimpleString("'/templates/',", "\t\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'oldFiles'  => [", "\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'oldFolders'  => [", "\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'renameTables'  => [", "\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'moduleStats'  => [", "\t");
        $ret    .= $this->getSimpleString("],", "\t");
        $ret    .= $this->getSimpleString("'modCopyright' => \"<a href='https://xoops.org' title='XOOPS Project' target='_blank'><img src='\" . \XOOPS_ROOT_PATH . '/modules/' . \$moduleDirName . \"/assets/images/logo/logoModule.png' alt='XOOPS Project'></a>\",", "\t");
        $ret    .= $this->getSimpleString('];');

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
        $content       .= $this->getConfigCode();
        $this->create($moduleDirname, 'config', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

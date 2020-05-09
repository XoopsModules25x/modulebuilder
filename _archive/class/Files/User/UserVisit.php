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
 * Class UserVisit.
 */
class UserVisit extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $uxc = null;

    /**
     * @var string
     */
    private $xc = null;
	
	/**
     * @var string
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
        $this->uxc = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserVisit
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
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserVisitHeader
     *
     * @param $table
     *
     * @param $fields
     * @return string
     */
    private function getUserVisitHeader($table, $fields)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $module        = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
        $ret = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret .= $this->getInclude();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }
        if (1 == $table->getVar('table_category')) {
            $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
            $ret        .= $this->xc->getXcXoopsRequest($ccFieldPid, (string)$fieldPid, '0', 'Int');
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       .= $this->xc->getXcXoopsRequest($ccFieldId, (string)$fieldId, '0', 'Int');

        return $ret;
    }

    /**
     * @private function getUserVisitCheckPermissions
     *
     * @param null
     *
     * @return string
     */
    private function getUserVisitCheckPermissions()
    {
        $ret = '';

        return $ret;
    }

    /**
     * @private function getUserVisitCheckLimit
     *
     * @param null
     *
     * @return string
     */
    private function getUserVisitCheckLimit()
    {
        $ret = '';

        return $ret;
    }

    /**
     * @private function getUserVisitCheckHost
     *
     * @param null
     *
     * @return string
     */
    private function getUserVisitCheckHost()
    {
        $ret = '';

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
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId       = $table->getVar('table_id');
        $tableMid      = $table->getVar('table_mid');
        $fields        = $this->getTableFields($tableMid, $tableId);
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserVisitHeader($table, $fields);
        $content       .= $this->getUserVisitCheckPermissions();
        $content       .= $this->getUserVisitCheckLimit();
        $content       .= $this->getUserVisitCheckHost();

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}

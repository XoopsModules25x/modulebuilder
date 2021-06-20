<?php

namespace XoopsModules\Modulebuilder\Files;

use XoopsModules\Modulebuilder;

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
 * AbstractClass base class.
 */
abstract class CreateAbstractClass
{
    /**
     * "module" attribute fot files.
     *
     * @var mixed
     */
    protected $module;

    /**
     * "table" attribute fot files.
     *
     * @var mixed
     */
    protected $table;

    /**
     * "tables" attribute fot files.
     *
     * @var mixed
     */
    protected $tables;

    /**
     * "fields" attribute fot files.
     *
     * @var mixed
     */
    protected $fields;

    /**
     * public function setFileName.
     *
     * @param mixed $module
     */
    public function setModule($module)
    {
        if (\is_object($module) && ($module instanceof ModuleBuilder\Modules)) {
            $this->module = $module;
        }
    }

    /**
     * @public function getModule
     *
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * public function setTable.
     *
     * @param mixed $table
     */
    public function setTable($table)
    {
        if (\is_object($table) && ($table instanceof Modulebuilder\Tables)) {
            $this->table = $table;
        }
    }

    /**
     * @public function getTable
     *
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * public function setTables.
     *
     * @param mixed $tables
     */
    public function setTables($tables)
    {
        if (\is_array($tables)) {
            $this->tables = $tables;
        }
    }

    /**
     * @public function getTables
     *
     * @return mixed
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * @public function setFields
     *
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        if (\is_object($fields) && ($fields instanceof Modulebuilder\Fields)) {
            $this->fields = $fields;
        }
    }

    /**
     * @public function getFields
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Generates output for files.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @abstract
     */
    public function render()
    {
    }
}

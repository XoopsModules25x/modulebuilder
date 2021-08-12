<?php

namespace XoopsModules\Modulebuilder;

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
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <https://xoops.org/>
 *
 */

/**
 * @Class TablesHandler
 * @extends \XoopsPersistableObjectHandler
 */
class TablesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     *
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_tables', Tables::class, 'table_id', 'table_name');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field.
     *
     * @param int  $i field id
     * @param null $fields
     *
     * @return mixed reference to the <a href='psi_element://Fields'>Fields</a> object
     *               object
     */
    public function get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id.
     *
     * @param null
     *
     * @return int reference to the {@link Tables} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Modules.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountTables($start = 0, $limit = 0, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $crCountTables = new \CriteriaCompo();
        $crCountTables = $this->getTablesCriteria($crCountTables, $start, $limit, $sort, $order);

        return $this->getCount($crCountTables);
    }

    /**
     * Get All Modules.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllTables($start = 0, $limit = 0, $sort = 'table_mid DESC, table_order ASC, table_id', $order = 'DESC')
    {
        $crAllTables = new \CriteriaCompo();
        $crAllTables = $this->getTablesCriteria($crAllTables, $start, $limit, $sort, $order);

        return $this->getAll($crAllTables);
    }

    /**
     * Get All Tables By Module Id.
     *
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllTablesByModuleId($modId, $start = 0, $limit = 0, $sort = 'table_order ASC, table_id, table_name', $order = 'ASC')
    {
        $crAllTablesByModuleId = new \CriteriaCompo();
        $crAllTablesByModuleId->add(new \Criteria('table_mid', $modId));
        $crAllTablesByModuleId = $this->getTablesCriteria($crAllTablesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($crAllTablesByModuleId);
    }

    /**
     * Get Tables Criteria.
     *
     * @param $crTables
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getTablesCriteria($crTables, $start, $limit, $sort, $order)
    {
        $crTables->setStart($start);
        $crTables->setLimit($limit);
        $crTables->setSort($sort);
        $crTables->setOrder($order);

        return $crTables;
    }
}

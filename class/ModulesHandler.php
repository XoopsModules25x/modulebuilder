<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * modules class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <https://xoops.org>
 */

/**
 * @Class ModulesHandler
 * @extends \XoopsPersistableObjectHandler
 */
class ModulesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     *
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_modules', Modules::class, 'mod_id', 'mod_name');
    }

    /**
     * @param bool $isNew
     *
     * @return \XoopsObject
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
    public function getCountModules($start = 0, $limit = 0, $sort = 'mod_id', $order = 'DESC')
    {
        $crCountModules = new \CriteriaCompo();
        $crCountModules = $this->getModulesCriteria($crCountModules, $start, $limit, $sort, $order);

        return $this->getCount($crCountModules);
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
    public function getAllModules($start = 0, $limit = 0, $sort = 'mod_id', $order = 'DESC')
    {
        $crAllModules = new \CriteriaCompo();
        $crAllModules = $this->getModulesCriteria($crAllModules, $start, $limit, $sort, $order);

        return $this->getAll($crAllModules);
    }

    /**
     * Get Modules Criteria.
     *
     * @param $crModules
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getModulesCriteria($crModules, $start, $limit, $sort, $order)
    {
        $crModules->setStart($start);
        $crModules->setLimit($limit);
        $crModules->setSort($sort);
        $crModules->setOrder($order);

        return $crModules;
    }
}

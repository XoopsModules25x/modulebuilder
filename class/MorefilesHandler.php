<?php

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
 * morefiles class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 */

/**
 * @Class MorefilesHandler
 * @extends \XoopsPersistableObjectHandler
 */
class MorefilesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     *
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_morefiles', Morefiles::class, 'file_id', 'file_name');
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
     * Get Count Morefiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountMorefiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMorefilesCount = new \CriteriaCompo();
        $crMorefilesCount = $this->getMorefilesCriteria($crMorefilesCount, $start, $limit, $sort, $order);

        return $this->getCount($crMorefilesCount);
    }

    /**
     * Get All Morefiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMorefiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMorefilesAdd = new \CriteriaCompo();
        $crMorefilesAdd = $this->getMorefilesCriteria($crMorefilesAdd, $start, $limit, $sort, $order);

        return $this->getAll($crMorefilesAdd);
    }

    /**
     * Get All Morefiles By Module Id.
     *
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMorefilesByModuleId($modId, $start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMorefilesByModuleId = new \CriteriaCompo();
        $crMorefilesByModuleId->add(new \Criteria('file_mid', $modId));
        $crMorefilesByModuleId = $this->getMorefilesCriteria($crMorefilesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($crMorefilesByModuleId);
    }

    /**
     * Get Morefiles Criteria.
     *
     * @param $crMorefiles
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getMorefilesCriteria($crMorefiles, $start, $limit, $sort, $order)
    {
        $crMorefiles->setStart($start);
        $crMorefiles->setLimit($limit);
        $crMorefiles->setSort($sort);
        $crMorefiles->setOrder($order);

        return $crMorefiles;
    }
}

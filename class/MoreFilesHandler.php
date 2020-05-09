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
//include __DIR__.'/autoload.php';

/**
 * @Class MoreFilesHandler
 * @extends \XoopsPersistableObjectHandler
 */
class MoreFilesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     *
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_morefiles', MoreFiles::class, 'file_id', 'file_name');
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
     * Get Count MoreFiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountMoreFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesCount = new \CriteriaCompo();
        $crMoreFilesCount = $this->getMoreFilesCriteria($crMoreFilesCount, $start, $limit, $sort, $order);

        return $this->getCount($crMoreFilesCount);
    }

    /**
     * Get All MoreFiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMoreFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesAdd = new \CriteriaCompo();
        $crMoreFilesAdd = $this->getMoreFilesCriteria($crMoreFilesAdd, $start, $limit, $sort, $order);

        return $this->getAll($crMoreFilesAdd);
    }

    /**
     * Get All MoreFiles By Module Id.
     *
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMoreFilesByModuleId($modId, $start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesByModuleId = new \CriteriaCompo();
        $crMoreFilesByModuleId->add(new \Criteria('file_mid', $modId));
        $crMoreFilesByModuleId = $this->getMoreFilesCriteria($crMoreFilesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($crMoreFilesByModuleId);
    }

    /**
     * Get MoreFiles Criteria.
     *
     * @param $crMoreFiles
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getMoreFilesCriteria($crMoreFiles, $start, $limit, $sort, $order)
    {
        $crMoreFiles->setStart($start);
        $crMoreFiles->setLimit($limit);
        $crMoreFiles->setSort($sort);
        $crMoreFiles->setOrder($order);

        return $crMoreFiles;
    }
}

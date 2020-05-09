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
 * @copyright       The XOOPS Project http:sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http:www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http:www.txmodxoops.org/>
 *
 */
//include __DIR__.'/autoload.php';

/**
 * Class MoreFilesHandler.
 */
class AddFilesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_addfiles', AddFiles::class, 'file_id', 'file_name');
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
     * @return mixed reference to the <a href='psi_element:Fields'>Fields</a> object
     *               object
     */
    public function &get($i = null, $fields = null)
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
    public function &getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * insert a new field in the database.
     *
     * @param \XoopsObject $field reference to the {@link Fields} object
     * @param bool         $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $field, $force = false)
    {
        if (!parent::insert($field, $force)) {
            return false;
        }

        return true;
    }

    /**
     * Get Count AddFiles.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountAddFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $criteriaAddFilesCount = new \CriteriaCompo();
        $criteriaAddFilesCount = $this->getAddFilesCriteria($criteriaAddFilesCount, $start, $limit, $sort, $order);

        return $this->getCount($criteriaAddFilesCount);
    }

    /**
     * Get All AddFiles.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllAddFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $criteriaAddFilesAdd = new \CriteriaCompo();
        $criteriaAddFilesAdd = $this->getAddFilesCriteria($criteriaAddFilesAdd, $start, $limit, $sort, $order);

        return $this->getAll($criteriaAddFilesAdd);
    }

    /**
     * Get All AddFiles By Module Id.
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllAddFilesByModuleId($modId, $start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $criteriaAddFilesByModuleId = new \CriteriaCompo();
        $criteriaAddFilesByModuleId->add(new \Criteria('file_mid', $modId));
        $criteriaAddFilesByModuleId = $this->getAddFilesCriteria($criteriaAddFilesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($criteriaAddFilesByModuleId);
    }

    /**
     * Get AddFiles Criteria.
     * @param $criteriaAddFiles
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     * @return mixed
     */
    private function getAddFilesCriteria($criteriaAddFiles, $start, $limit, $sort, $order)
    {
        $criteriaAddFiles->setStart($start);
        $criteriaAddFiles->setLimit($limit);
        $criteriaAddFiles->setSort($sort);
        $criteriaAddFiles->setOrder($order);

        return $criteriaAddFiles;
    }
}

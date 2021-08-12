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
 * modulebuilderreate module.
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
 * Class FieldsHandler.
 */
class FieldsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     *
     * @param mixed|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_fields', Fields::class, 'field_id', 'field_name');
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
     * @return int reference to the {@link Fields} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Fields.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountFields($start = 0, $limit = 0, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $crCountFields = new \CriteriaCompo();
        $crCountFields = $this->getFieldsCriteria($crCountFields, $start, $limit, $sort, $order);

        return $this->getCount($crCountFields);
    }

    /**
     * Get All Fields.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllFields($start = 0, $limit = 0, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $crAllFields = new \CriteriaCompo();
        $crAllFields = $this->getFieldsCriteria($crAllFields, $start, $limit, $sort, $order);

        return $this->getAll($crAllFields);
    }

    /**
     * Get All Fields By Module & Table Id.
     * @param        $tabId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllFieldsByTableId($tabId, $start = 0, $limit = 0, $sort = 'field_order ASC, field_id, field_name', $order = 'ASC')
    {
        $crAllFieldsByModule = new \CriteriaCompo();
        $crAllFieldsByModule->add(new \Criteria('field_tid', $tabId));
        $crAllFieldsByModule = $this->getFieldsCriteria($crAllFieldsByModule, $start, $limit, $sort, $order);

        return $this->getAll($crAllFieldsByModule);
    }

    /**
     * Get Fields Criteria.
     * @param $crFields
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     * @return mixed
     */
    private function getFieldsCriteria($crFields, $start, $limit, $sort, $order)
    {
        $crFields->setStart($start);
        $crFields->setLimit($limit);
        $crFields->setSort($sort);
        $crFields->setOrder($order);

        return $crFields;
    }
}

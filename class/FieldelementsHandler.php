<?php declare(strict_types=1);

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
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 */

/**
 * Class FieldelementsHandler.
 */
class FieldelementsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @public function constructor class
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_fieldelements', Fieldelements::class, 'fieldelement_id', 'fieldelement_name');
    }

    /**
     * Get Count Fields.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountFieldelements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crCountFieldElems = new \CriteriaCompo();
        $crCountFieldElems = $this->getFieldelementsCriteria($crCountFieldElems, $start, $limit, $sort, $order);

        return parent::getCount($crCountFieldElems);
    }

    /**
     * Get Objects Fields.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getObjectsFieldelements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crObjectsFieldElems = new \CriteriaCompo();
        $crObjectsFieldElems = $this->getFieldelementsCriteria($crObjectsFieldElems, $start, $limit, $sort, $order);

        return $this->getObjects($crObjectsFieldElems);
    }

    /**
     * Get All Fields.
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllFieldelements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crAllFieldElems = new \CriteriaCompo();
        $crAllFieldElems = $this->getFieldelementsCriteria($crAllFieldElems, $start, $limit, $sort, $order);

        return $this->getAll($crAllFieldElems);
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
    public function getAllFieldelementsByTableId($tabId, $start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crAllFieldElemsByModule = new \CriteriaCompo();
        $crAllFieldElemsByModule->add(new \Criteria('fieldelement_tid', $tabId));
        $crAllFieldElemsByModule = $this->getFieldelementsCriteria($crAllFieldElemsByModule, $start, $limit, $sort, $order);

        return $this->getAll($crAllFieldElemsByModule);
    }

    /**
     * Get Fieldelements Criteria.
     * @param $crFieldElemsCriteria
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     * @return mixed
     */
    private function getFieldelementsCriteria($crFieldElemsCriteria, $start, $limit, $sort, $order)
    {
        $crFieldElemsCriteria->setStart($start);
        $crFieldElemsCriteria->setLimit($limit);
        $crFieldElemsCriteria->setSort($sort);
        $crFieldElemsCriteria->setOrder($order);

        return $crFieldElemsCriteria;
    }

    /**
     * Get Fieldelements Criteria.
     * @param $crFieldElemsCriteria
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     * @return mixed
     */
    public function getFieldelementsList($crFieldElemsCriteria, $start = 0, $limit = 0, $sort = 'fieldelement_sort', $order = 'ASC')
    {
        $crFieldElems = $this->getFieldelementsCriteria($crFieldElemsCriteria, $start, $limit, $sort, $order);
        $fieldeleArr  = $this->getAll($crFieldElems);
        $fieldele     = [];
        foreach (\array_keys($fieldeleArr) as $i) {
            $stuFeName = \mb_strtoupper($fieldeleArr[$i]->getVar('fieldelement_name'));
            if (1 == $i) {
                $fieldele[$i] = '...';
            } else {
                $fieldele[$i] = constant('\_AM_MODULEBUILDER_FIELD_ELE_' . $stuFeName);
            }
        }

        return $fieldele;
    }
}

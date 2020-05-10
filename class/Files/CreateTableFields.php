<?php

namespace XoopsModules\Modulebuilder\Files;

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
 * Class CreateTableFields
 */
class CreateTableFields extends Files\CreateAbstractClass
{
    /**
     * @public   function constructor
     */
    public function __construct()
    {
    }

    /**
     * @static function getInstance
     *
     * @return bool|\ModuleBuilderTableFields
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
     * @public function getTableTables
     *
     * @param        $mId
     *
     * @param string $sort
     * @param string $order
     * @return mixed
     */
    public function getTableTables($mId, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('table_mid', $mId)); // $mId = module Id
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $tables = Modulebuilder\Helper::getInstance()->getHandler('tables')->getObjects($criteria);
        unset($criteria);

        return $tables;
    }

    /**
     * @public function getTableFields
     *
     * @param        $mId
     * @param        $tId
     *
     * @param string $sort
     * @param string $order
     * @return mixed
     */
    public function getTableFields($mId, $tId, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('field_mid', $mId)); // $mId = module Id
        $criteria->add(new \Criteria('field_tid', $tId)); // $tId = table Id
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $fields = Modulebuilder\Helper::getInstance()->getHandler('fields')->getObjects($criteria);
        unset($criteria);

        return $fields;
    }

    /**
     * @public function getTableFieldelements
     *
     * @param        $mId
     * @param        $tId
     *
     * @param string $sort
     * @param string $order
     * @return mixed
     */
    public function getTableFieldelements($mId = null, $tId = null, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        if (null != $mId) {
            $criteria->add(new \Criteria('fieldelement_mid', $mId)); // $mId = module Id
            $criteria->setSort($sort);
            $criteria->setOrder($order);
        }
        if (null != $tId) {
            $criteria->add(new \Criteria('fieldelement_tid', $tId)); // $tId = table Id
            $criteria->setSort($sort);
            $criteria->setOrder($order);
        }
        $fieldElements = Modulebuilder\Helper::getInstance()->getHandler('fieldelements')->getObjects($criteria);
        unset($criteria);

        return $fieldElements;
    }

    /**
     * @public function getTableMorefiles
     *
     * @param        $mId
     *
     * @param string $sort
     * @param string $order
     * @return mixed
     */
    public function getTableMorefiles($mId, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_mid', $mId)); // $mId = module Id
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $morefiles = Modulebuilder\Helper::getInstance()->getHandler('morefiles')->getObjects($criteria);
        unset($criteria);

        return $morefiles;
    }
}

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
 * Modulebuilder module for xoops
 *
 * @copyright      module for xoops
 * @license        GPL 3.0 or later
 * @since          1.0
 * @min_xoops      2.5.10
 * @author         XOOPS Development Team
 */
\defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Object Ratings
 */
class Ratings extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('rate_id', \XOBJ_DTYPE_INT);
        $this->initVar('rate_source', \XOBJ_DTYPE_INT);
        $this->initVar('rate_itemid', \XOBJ_DTYPE_INT);
        $this->initVar('rate_value', \XOBJ_DTYPE_INT);
        $this->initVar('rate_uid', \XOBJ_DTYPE_INT);
        $this->initVar('rate_ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('rate_date', \XOBJ_DTYPE_INT);
    }

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance(): void
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * The new inserted $Id
     * @return int inserted id
     */
    public function getNewInsertedIdRatings()
    {
        $newInsertedId = $GLOBALS['xoopsDB']->getInsertId();

        return $newInsertedId;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesRatings($keys = null, $format = null, $maxDepth = null)
    {
        $ret           = $this->getValues($keys, $format, $maxDepth);
        $ret['id']     = $this->getVar('rate_id');
        $ret['source'] = $this->getVar('rate_source');
        $ret['itemid'] = $this->getVar('rate_itemid');
        $ret['value']  = $this->getVar('rate_value');
        $ret['uid']    = \XoopsUser::getUnameFromId($this->getVar('rate_uid'));
        $ret['ip']     = $this->getVar('rate_ip');
        $ret['date']   = \formatTimestamp($this->getVar('rate_date'), 's');

        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayRatings()
    {
        $ret  = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar('"{$var}"');
        }

        return $ret;
    }
}

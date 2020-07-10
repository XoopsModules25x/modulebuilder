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
 * Modulebuilder module for xoops
 *
 * @copyright      module for xoops
 * @license        GPL 3.0 or later
 * @package        Modulebuilder
 * @since          1.0
 * @min_xoops      2.5.10
 * @author         XOOPS Development Team
 */
\defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Object RatingsHandler
 */
class RatingsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_ratings', Ratings::class, 'rate_id', 'rate_itemid');
    }

    /**
     * @param bool $isNew
     *
     * @return Object
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field
     *
     * @param int   $i field id
     * @param array $fields
     * @return mixed reference to the {@link Get} object
     */
    public function get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id
     *
     * @param null
     * @return int reference to the {@link Get} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Rating per item in the database
     * @param int $itemid
     * @param int $source
     * @return array
     */
    public function getItemRating($itemid = 0, $source = 0)
    {
        $helper = \XoopsModules\Modulebuilder\Helper::getInstance();

        $ItemRating               = [];
        $ItemRating['nb_ratings'] = 0;
        $uid                      = \is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
        $voted                    = false;
        $ip                       = getenv('REMOTE_ADDR');
        $current_rating           = 0;

        if (Constants::RATING_5STARS === (int)$helper->getConfig('ratingbars')
            || Constants::RATING_10STARS === (int)$helper->getConfig('ratingbars')
            || Constants::RATING_10NUM === (int)$helper->getConfig('ratingbars')) {
            $rating_unitwidth = 25;
            if (Constants::RATING_5STARS === (int)$helper->getConfig('ratingbars')) {
                $max_units = 5;
            } else {
                $max_units = 10;
            }

            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('rate_itemid', $itemid));
            $criteria->add(new \Criteria('rate_source', $source));

            $ratingObjs               = $helper->getHandler('ratings')->getObjects($criteria);
            $count                    = \count($ratingObjs);
            $ItemRating['nb_ratings'] = $count;

            foreach ($ratingObjs as $ratingObj) {
                $current_rating += $ratingObj->getVar('rate_value');
                if (($ratingObj->getVar('rate_ip') == $ip && $uid == 0) || ($uid > 0 && $uid == $ratingObj->getVar('rate_uid'))) {
                    $voted            = true;
                    $ItemRating['id'] = $ratingObj->getVar('rate_id');
                }
            }
            unset($ratingObj);
            unset($criteria);

            $ItemRating['avg_rate_value'] = 0;
            if ($count > 0) {
                $ItemRating['avg_rate_value'] = number_format($current_rating / $count, 2);
            }
            if (1 == $count) {
                $text      = \str_replace('%c', $ItemRating['avg_rate_value'], _MA_MODULEBUILDER_RATING_CURRENT_1);
                $shorttext = \str_replace('%c', $ItemRating['avg_rate_value'], _MA_MODULEBUILDER_RATING_CURRENT_SHORT_1);
            } else {
                $text      = \str_replace('%c', $ItemRating['avg_rate_value'], _MA_MODULEBUILDER_RATING_CURRENT_X);
                $shorttext = \str_replace('%c', $ItemRating['avg_rate_value'], _MA_MODULEBUILDER_RATING_CURRENT_SHORT_X);
            }
            $text                    = \str_replace('%m', $max_units, $text);
            $text                    = \str_replace('%t', $ItemRating['nb_ratings'], $text);
            $shorttext               = \str_replace('%t', $ItemRating['nb_ratings'], $shorttext);
            $ItemRating['text']      = $text;
            $ItemRating['shorttext'] = $shorttext;
            $ItemRating['size']      = ($ItemRating['avg_rate_value'] * $rating_unitwidth) . 'px';
            $ItemRating['maxsize']   = ($max_units * $rating_unitwidth) . 'px';
        } else {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('rate_itemid', $itemid));
            $criteria->add(new \Criteria('rate_source', $source));
            $criteria->add(new \Criteria('rate_value', 0, '<'));

            $ratingObjs = $helper->getHandler('ratings')->getObjects($criteria);
            $count      = \count($ratingObjs);

            foreach ($ratingObjs as $ratingObj) {
                $current_rating += $ratingObj->getVar('rate_value');
                if (($ratingObj->getVar('rate_ip') == $ip && $uid == 0) || ($uid > 0 && $uid == $ratingObj->getVar('rate_uid'))) {
                    $voted            = true;
                    $ItemRating['id'] = $ratingObj->getVar('rate_id');
                }
            }
            unset($ratingObj);
            unset($criteria);
            $ItemRating['dislikes'] = $count;

            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('rate_itemid', $itemid));
            $criteria->add(new \Criteria('rate_source', $source));
            $criteria->add(new \Criteria('rate_value', 0, '>'));

            $ratingObjs     = $helper->getHandler('ratings')->getObjects($criteria);
            $count          = \count($ratingObjs);
            $current_rating = 0;
            foreach ($ratingObjs as $ratingObj) {
                $current_rating += $ratingObj->getVar('rate_value');
                if (($ratingObj->getVar('rate_ip') == $ip && $uid == 0) || ($uid > 0 && $uid == $ratingObj->getVar('rate_uid'))) {
                    $voted            = true;
                    $ItemRating['id'] = $ratingObj->getVar('rate_id');
                }
            }
            unset($ratingObj);
            unset($criteria);
            $ItemRating['likes'] = $count;

            $count = $ItemRating['likes'] + $ItemRating['dislikes'];
        }

        $ItemRating['uid']        = $uid;
        $ItemRating['nb_ratings'] = $count;
        $ItemRating['voted']      = $voted;
        $ItemRating['ip']         = $ip;

        return $ItemRating;
    }

    /**
     * delete ratings of given item
     * @param mixed $itemid
     * @param mixed $source
     * @return bool
     */
    public function deleteAllRatings($itemid, $source)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('rate_itemid', $itemid));
        $criteria->add(new \Criteria('rate_source', $source));

        return $this->deleteAll($criteria);
    }
}

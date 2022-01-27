<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Html;

/**
 * XOOPS form element.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.0.0
 *
 * @author          Kazumi Ono (AKA onokazu) https://www.myweb.ne.jp/, https://jp.xoops.org/
 */
\defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * A text label.
 */
class FormLabel extends \XoopsFormElement
{
    /**
     * @param string $caption
     */
    public function __construct($caption = '')
    {
        $this->setCaption($caption);
    }

    /**
     * Prepare HTML for output.
     *
     * @return string
     */
    public function render()
    {
        return $this->getCaption();
    }
}

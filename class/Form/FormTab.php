<?php

namespace XoopsModules\Modulebuilder\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Tab - a form tab.
 *
 * @category  XoopsFormTab
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2014 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @link      https://xoops.org
 * @since     2.0.0
 */
\XoopsLoad::load('XoopsFormElementTray');

/**
 * Class Modulebuilder\FormTab.
 */
class FormTab extends \XoopsFormElementTray
{
    /**
     * __construct.
     *
     * @param string $caption tab caption
     * @param string $name    unique identifier for this tab
     */
    public function __construct($caption, $name)
    {
        $this->setName($name);
        $this->setCaption($caption);
    }

    /**
     * render.
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        /* @var \XoopsFormElement $ele */
        foreach ($this->getElements() as $ele) {
            $ret         .= NWLINE;
            $ret         .= '<tr>' . NWLINE;
            $ret         .= '<td class="head" style="width:30%">' . NWLINE;
            $required    = $ele->isRequired() ? '-required' : '';
            $ret         .= '<div class="xoops-form-element-caption' . $required . '">' . NWLINE;
            $ret         .= '<span class="caption-text">' . $ele->getCaption() . '</span>' . NWLINE;
            $ret         .= '<span class="caption-marker">*</span>' . NWLINE;
            $ret         .= '</div>' . NWLINE;
            $description = $ele->getDescription();
            if ($description) {
                $ret .= '<div style="font-weight: normal">' . NWLINE;
                $ret .= $description . NWLINE;
                $ret .= '</div>' . NWLINE;
            }
            $ret .= '</td>' . NWLINE;
            $ret .= '<td class="even">' . NWLINE;
            $ret .= $ele->render() . NWLINE;
            $ret .= '<span class="form-horizontal">' . $ele->getDescription() . '</span>';
            $ret .= '</td>' . NWLINE;
            $ret .= '</tr>' . NWLINE;
        }

        return $ret;
    }
}

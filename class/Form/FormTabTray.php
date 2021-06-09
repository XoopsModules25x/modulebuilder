<?php

namespace XoopsModules\Modulebuilder\Form;

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
 * TabTray - a form tray for tabs.
 *
 * @category  XoopsFormTabTray
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2014 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @link      https://xoops.org
 * @since     2.0.0
 */
\XoopsLoad::load('XoopsFormElementTray');

/**
 * Class Modulebuilder\FormTabTray.
 */
class FormTabTray extends \XoopsFormElementTray
{
    /**
     * Theme to use for jquery UI.
     *
     * @var string
     */
    private $uiTheme = '';
    private $delimiter;

    /**
     * __construct.
     *
     * @param string $caption   tray caption
     * @param string $name      Unique identifier for this tray
     * @param string $uiTheme   Theme to use for jquery UI (remove? now set by theme)
     * @param string $delimiter delimiter
     */
    public function __construct($caption, $name, $uiTheme = 'base', $delimiter = '&nbsp;')
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->delimiter = $delimiter;
        $this->uiTheme   = $uiTheme;
    }

    /**
     * create HTML to output the form as a table.
     *
     * @return string
     */
    public function render()
    {
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addStylesheet(\XOOPS_URL . '/modules/system/css/ui/' . $this->uiTheme . '/ui.all.css');
        $GLOBALS['xoTheme']->addScript('', ['type' => 'text/javascript'], '$(function() { $("#tabs_' . $this->getName() . '").tabs(); });');

        $ret = '<div id="tabs_' . $this->getName() . '">' . NWLINE;
        $ret .= '<ul>' . NWLINE;
        foreach ($this->getElements() as $ele) {
            if ($ele instanceof Modulebuilder\Form\FormTab) {
                $ret .= '<li><a href="#tab_' . $ele->getName() . '"><span>' . $ele->getCaption() . '</span></a></li>' . NWLINE;
            }
        }
        $ret .= '</ul>' . NWLINE;

        $hidden = '';
        $extras = [];

        foreach ($this->getElements() as $ele) {
            /* @var \XoopsFormElement $ele */
            if (!$ele->isHidden()) {
                if (!$ele instanceof Modulebuilder\Form\FormRaw) {
                    if ($ele instanceof Modulebuilder\Form\FormTab) {
                        $ret .= '<div id="tab_' . $ele->getName() . '">' . NWLINE;
                        $ret .= '<table class="outer" style="border-spacing:5px;">' . NWLINE;
                        $ret .= $ele->render();
                        $ret .= '</table>' . NWLINE;
                        $ret .= '</div>' . NWLINE;
                    } else {
                        $extras[] = $ele;
                    }
                } else {
                    $ret .= $ele->render();
                }
            } else {
                $hidden .= $ele->render();
            }
        }
        if (!empty($extras)) {
            $tray = new \XoopsFormElementTray('', $this->delimiter);
            foreach ($extras as $extra) {
                $tray->addElement($extra);
            }
            $ret .= $tray->render();
            $ret .= NWLINE;
        }

        $ret .= $hidden . NWLINE;
        $ret .= '</div>' . NWLINE;

        return $ret;
    }
}

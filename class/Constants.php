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
 * @copyright     2020 XOOPS Project (https://xoops.org)
 * @license        GPL 2.0 or later
 * @package        Modulebuilder
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

/**
 * Interface  Constants
 */
interface Constants
{
	// Constants for morefiles
	const MORE_FILES_TYPE_EMPTY = 1;
	const MORE_FILES_TYPE_COPY = 2;

	const FIRST_FIELDELEMENT_TABLE = 30;

    // ------------------- Field elements ---------------------------------
    // --------- The values MUST BE IDENTICAL to fieldelement_id ----------
    const FIELD_ELE_TEXT           = 2;
    const FIELD_ELE_TEXTAREA       = 3;
    const FIELD_ELE_DHTMLTEXTAREA  = 4;
    const FIELD_ELE_CHECKBOX       = 5;
    const FIELD_ELE_RADIOYN        = 6;
    const FIELD_ELE_SELECTBOX      = 7;
    const FIELD_ELE_SELECTUSER     = 8;
    const FIELD_ELE_COLORPICKER    = 9;
    const FIELD_ELE_IMAGELIST      = 10;
    const FIELD_ELE_SELECTFILE     = 11;
    const FIELD_ELE_URLFILE        = 12;
    const FIELD_ELE_UPLOADIMAGE    = 13;
    const FIELD_ELE_UPLOADFILE     = 14;
    const FIELD_ELE_TEXTDATESELECT = 15;
    const FIELD_ELE_SELECTSTATUS   = 16;
    const FIELD_ELE_PASSWORD       = 17;
    const FIELD_ELE_SELECTCOUNTRY  = 18;
    const FIELD_ELE_SELECTLANG     = 19;
    const FIELD_ELE_RADIO          = 20;
    const FIELD_ELE_DATETIME       = 21;
    const FIELD_ELE_SELECTCOMBO    = 22;
    const FIELD_ELE_TEXTUUID       = 23;
    const FIELD_ELE_TEXTIP         = 24;
    const FIELD_ELE_TEXTCOMMENTS   = 25;
    const FIELD_ELE_TEXTRATINGS    = 26;
    const FIELD_ELE_TEXTVOTES      = 27;
    const FIELD_ELE_TEXTREADS      = 28;

}

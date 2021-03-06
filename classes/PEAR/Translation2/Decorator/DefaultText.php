<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Lorenzo Alberton <l dot alberton at quipo dot it>           |
// +----------------------------------------------------------------------+
//
// $Id: DefaultText.php,v 1.5 2004/10/28 10:16:25 quipo Exp $
//
/**
 * @package Translation2
 * @version $Id: DefaultText.php,v 1.5 2004/10/28 10:16:25 quipo Exp $
 */
/**
 * Load Translation2 decorator base class
 */
require_once 'Translation2/Decorator.php';

/**
 * Decorator to provide a fallback text for empty strings.
 * @package Translation2
 */
class Translation2_Decorator_DefaultText extends Translation2_Decorator
{

    // {{{ class vars

    /**
     * String appended to the returned string when the string is empty
     * and it's replaced by its $stringID. It can be used to mark unreplaced
     * strings.
     * @var string
     * @access protected
     */
    var $emptyPostfix = '';

    /**
     * String prepended to the returned string when the string is empty
     * and it's replaced by its $stringID. It can be used to mark unreplaced
     * strings.
     * @var string
     * @access protected
     */
    var $emptyPrefix = '';

    // }}}
    // {{{ get()

    /**
     * Get translated string
     *
     * If the string is empty, return the $defaultText if not empty,
     * the $stringID otherwise.
     *
     * @param string $stringID
     * @param string $pageID
     * @param string $langID
     * @param string $defaultText Text to display when the string is empty
     * @return string
     */
    function get($stringID, $pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null, $defaultText='')
    {
        $str = $this->translation2->get($stringID, $pageID, $langID);
        if (empty($str)) {
            $str = (empty($defaultText) ? $this->emptyPrefix.$stringID.$this->emptyPostfix : $defaultText);
        }
        return $str;
    }

    // }}}
    // {{{ getPage()

    /**
     * Replace empty strings with their $stringID
     *
     * @param string $pageID
     * @param string $langID
     * @return array
     */
    function getPage($pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null)
    {
        $data = $this->translation2->getPage($pageID, $langID);
        return $this->replaceEmptyStringsWithKeys($data);
    }

    // }}}
    // {{{ getStringID

    /**
     * Get the stringID for the given string. This method is the reverse of get().
     * If the requested string is unknown to the system,
     * the requested string will be returned.
     *
     * @param string $string This is NOT the stringID, this is a real string.
     *               The method will search for its matching stringID, and then
     *               it will return the associate string in the selected language.
     * @param string $pageID
     * @return string
     */
    function &getStringID($string, $pageID=TRANSLATION2_DEFAULT_PAGEID)
    {
        if ($pageID == TRANSLATION2_DEFAULT_PAGEID) {
            $pageID = $this->translation2->currentPageID;
        }
        $stringID = $this->storage->getStringID($string, $pageID);
        if (empty($stringID)) {
            $stringID = $string;
        }
        return $stringID;
    }
}
?>
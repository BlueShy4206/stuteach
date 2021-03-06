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
// | Author:  Alan Knowles <alan@akbkhome.com>                            |
// +----------------------------------------------------------------------+
//
// $Id: dataobjectsimple.php,v 1.9 2004/12/14 20:03:33 quipo Exp $
//
/**
* @package Translation2
* @version $Id: dataobjectsimple.php,v 1.9 2004/12/14 20:03:33 quipo Exp $
*/

/**
* require Translation2_Container class and DB_DataObjects
*/
require_once 'Translation2/Container.php';
require_once 'DB/DataObject.php';

/**
 * Storage driver for fetching data from a simple dataobject
 *
 * Database Structure:
 *
 *  // meta data etc. not supported yet...
 *
 *  create table translations (
 *     id int(11) auto_increment not null primary key,
 *     string_id int(11),
 *     page varchar(128),
 *     lang varchar(10),
 *     translation text
 *     );
 * alter table translations add index page (page);
 * alter table translations add index lang (lang);
 * alter table translations add index string_id (string_id);
 *
 * - then just run the dataobjects createtables script.
 *
 *
 * This storage driver can use all databases which are supported
 * by the PEAR DB abstraction layer to fetch data.
 *
 * @package  Translation2
 * @version  $Revision: 1.9 $
 */
class Translation2_Container_dataobjectsimple extends Translation2_Container
{

    // {{{ class vars

    // }}}
    // {{{ init

    /**
     * Initialize the container
     *
     * @param  string table name
     * @return boolean true
     */
    function init($table = null)
    {
        $this->_setDefaultOptions();
        if (!empty($table)) {
            $this->options['table'] = $table;
        }
        return true;
    }

    // }}}
    // {{{ _setDefaultOptions()

    /**
     * Set some default options
     *
     * @access private
     * @return void
     */
    function _setDefaultOptions()
    {
        $this->options['table'] = 'translations';
    }

    // }}}
    // {{{ fetchLangs()

    /**
     * Fetch the available langs if they're not cached yet.
     */
    function fetchLangs()
    {
        $do = DB_DataObject::factory($this->options['table']);
        $do->selectAdd();
        $do->selectAdd('distinct lang');
        $do->find();

        $ret = array();
        while ($do->fetch()) {
            $l = $do->lang;
            $ret[$l] = array(
                'id'         => $l,
                'name'       => $l,
                'meta'       => '',
                'error_text' => '',
            );
        }
        $this->langs =  $ret;
    }

    // }}}
    // {{{ getPage()

    /**
     * Returns an array of the strings in the selected page
     *
     * @param string $pageID
     * @param string $langID
     * @return array
     */
    function &getPage($pageID = null, $langID = null)
    {
        $langID = $this->_getLangID($langID);

        $do = DB_DataObject::factory($this->options['table']);
        $do->lang = $langID;
        $do->page = $pageID;

        $do->find();
        $strings = array();
        while ($do->fetch()) {
            $strings[$do->string_id] = $do->translation;
        }

        return $strings;
    }

    // }}}
    // {{{ getOne()

    /**
     * Get a single item from the container, without caching the whole page
     *
     * @param string $stringID
     * @param string $pageID
     * @param string $langID
     * @return string
     */
    function getOne($string, $pageID = null, $langID = null)
    {
        $langID = $langID ? $langID : (isset($this->currentLang['id']) ? $this->currentLang['id'] : '-');
        // get the string id
        $do = DB_DataObject::factory($this->options['table']);
        $do->lang = '-';
        $do->page = $pageID;
        $do->translation = $string;
        // we dont have the base language translation..
        if (!$do->find(true)) {
            return '';
        }
        $stringID = $do->string_id;

        $do = DB_DataObject::factory($this->options['table']);
        $do->lang = $langID;
        $do->page = $pageID;
        $do->string_id = $stringID;
        //print_r($do);
        $do->selectAdd();
        $do->selectAdd('translation');
        if (!$do->find(true)) {
            return '';
        }
        return $do->translation;

    }

    // }}}
    // {{{ getStringID()

    /**
     * Get the stringID for the given string
     *
     * @param string $stringID
     * @param string $pageID
     * @return string
     */
    function getStringID($string, $pageID = null)
    {
        // get the english version...

        $do = DB_DataObject::factory($this->options['table']);
        $do->lang = $this->currentLang['id'];
        $do->page = $pageID;
        $do->translation = $string;
        if ($do->find(true)) {
            return '';
        }
        return $do->string_id;
    }

    // }}}
}
?>

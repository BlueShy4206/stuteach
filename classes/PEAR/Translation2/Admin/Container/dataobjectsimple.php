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
// $Id: dataobjectsimple.php,v 1.4 2005/01/17 21:53:32 quipo Exp $
//
/**
 * @package Translation2
 * @version $Id: dataobjectsimple.php,v 1.4 2005/01/17 21:53:32 quipo Exp $
 */

/**
 * require Translation2_Container_dataobjectsimple class
 */
require_once 'Translation2/Container/dataobjectsimple.php';

/**
 * Storage driver for storing/fetching data to/from a database
 *
 * Database Structure:
 *
 * // meta data etc. not supported
 *
 * table : translations
 *  id          // not null primary key autoincrement..
 *  string_id   // translation id
 *  page        // indexed varchar eg. (mytemplate.html)
 *  lang        // index varchar (eg. en|fr|.....)
 *  translation // the translated value in language lang.
 *
 *
 * This storage driver can use all databases which are supported
 * by the PEAR DB abstraction layer to fetch data.
 *
 * @package  Translation2
 * @version  $Revision: 1.4 $
 */
class Translation2_Admin_Container_dataobjectsimple extends Translation2_Container_dataobjectsimple
{
    // {{{ class vars

    // }}}
    // {{{ addLang()

    /**
     * Creates a new table to store the strings in this language.
     * If the table is shared with other langs, it is ALTERed to
     * hold strings in this lang too.
     *
     *
     * @param array $langData array('lang_id'    => 'en',
     *                              'table_name' => 'i18n',
     *                              'name'       => 'english',
     *                              'meta'       => 'some meta info',
     *                              'error_text' => 'not available');
     * @return mixed true on success, PEAR_Error on failure
     */
    function addLang($langData)
    {
        $do = DB_DataObject::factory($this->options['table']);
        $do->lang= $langData['lang_id'];
        if (!$do->find()) {
            $do->insert();
        }
    }

    // }}}
    // {{{ addLangToList()

    /**
     * Creates a new entry in the langsAvail table.
     * If the table doesn't exist yet, it is created.
     *
     * @param array $langData array('lang_id'    => 'en',
     *                              'table_name' => 'i18n',
     *                              'name'       => 'english',
     *                              'meta'       => 'some meta info',
     *                              'error_text' => 'not available');
     * @return true|PEAR_Error
     */
    function addLangToList($langData)
    {
        return true;
    }

    // }}}
    // {{{ add()

    /**
     * Add a new entry in the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @param array  $stringArray Associative array with string translations.
     *               Sample format:  array('en' => 'sample', 'it' => 'esempio')
     * @return true|PEAR_Error
     */
    function add($string, $page, $stringArray)
    {
        //look up the string id first..
        $do = DB_DataObject::factory($this->options['table']);
        $do->lang = '-';
        $do->translation = $string;
        $do->page = $page;
        if ($do->find(true)) {
            $stringID = $do->string_id;
        } else {
            // insert it and use the 'id' as the string id
            $stringID = $do->insert();
            $do->string_id = $stringID;
            $do->update();
        }

        foreach($stringArray as $lang=>$value) {
            $do = DB_DataObject::factory($this->options['table']);
            $do->string_id = $stringID;
            $do->page  = $page;
            $do->lang = $lang;
            if ($do->find(true)) {
                $do->translation = $value;
                $do->update();
                continue;
            }
            $do->translation = $value;
            $do->insert();
        }

        return true;
    }

    // }}}
    // {{{ update()

    /**
     * Update an existing entry in the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @param array  $stringArray Associative array with string translations.
     *               Sample format:  array('en' => 'sample', 'it' => 'esempio')
     * @return true|PEAR_Error
     */
    function update($stringID, $pageID, $stringArray)
    {
        $this->add($stringID, $pageID, $stringArray);
        return true;
    }

    // }}}
    // {{{ remove()

    /**
     * Remove an entry from the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @return true|PEAR_Error
     */
    function remove($stringID, $pageID)
    {
        // get the string id
        $do = DB_DataObject::factory($this->options['table']);
        $do->page = $pageID;
        $do->translation = $stringID;
        // we dont have the base language translation..
        if (!$do->find()) {
            return '';
        }

        while ($do->fetch()) {
            $do2 = DB_DataObject::factory($this->options['table']);
            $do2->get($do->id);
            $do2->delete();
        }
        return true;
    }

    // }}}
}
?>
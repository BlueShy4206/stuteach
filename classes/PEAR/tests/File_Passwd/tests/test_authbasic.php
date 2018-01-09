<?php
require_once 'System.php';
require_once 'PHPUnit.php';
require_once 'File/Passwd/Authbasic.php';

$GLOBALS['tmpfile'] = System::mktemp();

$GLOBALS['user']    = array(
'mike' => 'q4M4mpfilkNnU',
'pete' => 'dS80VTLQHZ6VM',
'mary' => 'jHSiqFjaEiKPM'
);

/**
 * TestCase for File_Passwd_AuthbasicTest class
 * Generated by PHPEdit.XUnit Plugin
 * 
 */
class File_Passwd_AuthbasicTest extends PHPUnit_TestCase{

    var $pwd;

    /**
     * Constructor
     * @param string $name The name of the test.
     * @access protected
     */
    function File_Passwd_AuthbasicTest($name){
        $this->PHPUnit_TestCase($name);
    }
    
    /**
     * Called before the test functions will be executed this function is defined in PHPUnit_TestCase and overwritten here
     * @access protected
     */
    function setUp(){
        $this->pwd = &new File_Passwd_Authbasic();
    }
    
    /**
     * Called after the test functions are executed this function is defined in PHPUnit_TestCase and overwritten here
     * @access protected
     */
    function tearDown(){
        $this->pwd = null;
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.File_Passwd_Authbasic method
     * @access public
     */
    function testFile_Passwd_Authbasic(){
        $this->assertTrue(is_a($this->pwd, 'File_Passwd_Authbasic'));
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.save method
     * @access public
     */
    function testsave(){
        $this->pwd->setFile($GLOBALS['tmpfile']);
        $this->pwd->_users = $GLOBALS['user'];
        $this->assertTrue($this->pwd->save());
        $this->assertEquals(file('passwd.authbasic.txt'), file($GLOBALS['tmpfile']));
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.addUser method
     * @access public
     */
    function testaddUser(){
        $this->assertTrue($this->pwd->addUser('add', 123));
        $this->assertTrue($this->pwd->userExists('add'));
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.changePasswd method
     * @access public
     */
    function testchangePasswd(){
        $this->pwd->addUser('change', 123);
        $this->assertTrue($this->pwd->changePasswd('change', 'abc'));
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.verifyPasswd method
     * @access public
     */
    function testverifyPasswd(){
        // DES
        $e = $this->pwd->setMode('des');
        if (strToUpper(substr(PHP_OS, 0,3)) == 'WIN') {
            $this->assertTrue(PEAR::isError($e));
        } else {
            $this->assertTrue($e);
            $this->pwd->addUser('des', 12345);
            $this->assertTrue($this->pwd->verifyPasswd('des', 12345));
            $this->assertFalse($this->pwd->verifyPasswd('des', ''));
        }
        // SHA
        $this->pwd->setMode('sha');
        $this->pwd->addUser('sha', 12345);
        $this->assertTrue($this->pwd->verifyPasswd('sha', 12345));
        $this->assertFalse($this->pwd->verifyPasswd('sha', ''));
        // MD5
        $this->pwd->setMode('md5');
        $this->pwd->addUser('md5', 12345);
        $this->assertTrue($this->pwd->verifyPasswd('md5', 12345));
        $this->assertFalse($this->pwd->verifyPasswd('md5', 2));
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.getMode method
     * @access public
     */
    function testgetMode(){
        $this->pwd->setMode('md5');
        $this->assertEquals('md5', $this->pwd->getMode());
        $this->pwd->setMode('sha');
        $this->assertEquals('sha', $this->pwd->getMode());
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.listModes method
     * @access public
     */
    function testlistModes(){
        $array = array('md5' => 'm', 'des' => 'd', 'sha' => 's');
        if (strToUpper(substr(PHP_OS, 0,3)) == 'WIN') {
            unset($array['des']);
        }
        $this->assertEquals(array_keys($array), $this->pwd->listModes());
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.setMode method
     * @access public
     */
    function testsetMode(){
        $this->pwd->setMode('md5');
        $this->assertEquals('md5', $this->pwd->getMode());
        $this->pwd->setMode('sha');
        $this->assertEquals('sha', $this->pwd->getMode());
    }
    
    /**
     * Regression test for File_Passwd_Authbasic.parse method
     * @access public
     */
    function testparse(){
        $this->pwd->setFile('passwd.authbasic.txt');
        $this->assertTrue($this->pwd->load());
        $this->assertEquals($GLOBALS['user'], $this->pwd->_users);
    }
    
    function teststaticAuth(){
        $this->assertTrue(true === File_Passwd::staticAuth('authbasic', 'passwd.authbasic.txt', 'mike', 123, 'des'));
        $this->assertTrue(false === File_Passwd::staticAuth('authbasic', 'passwd.authbasic.txt', 'mike', 'abc', 'des'));
        $this->assertFalse((File_Passwd::staticAuth('authbasic', 'passwd.authbasic.txt', 'nonexist', 'asd', 'des')));
    }
}
?>
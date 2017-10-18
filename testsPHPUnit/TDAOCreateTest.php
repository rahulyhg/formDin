<?php
require_once 'base/classes/webform/TDAOCreate.class.php';

/**
 * TDAOCreate test case.
 */
class TDAOCreateTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var TDAOCreate
     */
    private $tDAOCreate;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(){
        parent::setUp();        
        // TODO Auto-generated TDAOCreateTest::setUp()        
        $this->tDAOCreate = new TDAOCreate(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(){
        // TODO Auto-generated TDAOCreateTest::tearDown()
        $this->tDAOCreate = null;        
        parent::tearDown();
    }
    
    public function testSetShowScheme_true(){
        $esperado = true;
        $tDAOCreate = $this->tDAOCreate;
        $tDAOCreate->setShowScheme(true);
        $retorno = $tDAOCreate->getShowScheme();
        //$this->tDAOCreate->setShowScheme(true);
        //$retorno = $this->tDAOCreate->getShowScheme();        
        $this->assertEquals($esperado, $retorno);
    }
    
    public function testSetShowScheme_false(){
        $esperado = false;
        $this->tDAOCreate->setShowScheme(false);
        $retorno = $this->tDAOCreate->getShowScheme();
        $this->assertEquals($esperado, $retorno);
    }

    public function testHasScheme_yes(){
        $esperado = '\'.SCHEME.\'';        
        $tDAOCreate = $this->tDAOCreate;
        $tDAOCreate->setShowScheme(true);
        $retorno = $tDAOCreate->hasScheme();        
        $this->assertEquals($esperado, $retorno);
    }
    
    public function testHasScheme_no(){
        $esperado = '';
        $tDAOCreate = $this->tDAOCreate;
        $tDAOCreate->setShowScheme(false);
        $retorno = $tDAOCreate->hasScheme();  
        $this->assertEquals($esperado, $retorno);
    }

}
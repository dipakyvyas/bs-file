<?php
use BsFile\Controller\plugin\OutputFile;
use Test\Bootstrap;
use BsFile\Model\Mapper\ODM\Documents\File;
use Zend\InputFilter\InputFilter;
use BsFile\Controller\FileController;

require_once 'src/BsFile/Controller/Plugin/OutputFile.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * OutputFile test case.
 */
class OutputFileTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var OutputFile
     */
    private $OutputFile;

    /**
     *
     * @var File
     */
    private $file;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated OutputFileTest::setUp()
        
        $serviceManager = Bootstrap::getServiceManager();
        
        $this->file = new File();
        $this->file->setInputFilter(new InputFilter());
        
        $dm = $serviceManager->get('doctrine.documentmanager.odm_default');
        $repository = $dm->getRepository('BsFile\Model\Mapper\ODM\Documents\File');
        
        $repository->save($this->file);
        
        $this->OutputFile = new OutputFile();
        
        $this->OutputFile->setController(new FileController());
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated OutputFileTest::tearDown()
        $this->OutputFile = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests OutputFile->__invoke()
     */
    public function test__invoke()
    {
        $response = $this->OutputFile->__invoke($this->file);
        
        
        $this->assertInstanceOf('Zend\Http\PhpEnvironment\Response', $response);
    }
}


<?php

namespace academicpuma\citeproc\php;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-30 at 16:32:33.
 */
class CiteProcTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var CiteProc
     */
    protected $object;

    /**
     * @var array
     */
    protected $publications;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $pubs_folder = dirname('..') . CSLUtils::PUBLICATIONS_FOLDER;
        
        if ($handle = opendir($pubs_folder)) {
            while (false !== ($file = readdir($handle))) {
                if (!is_dir($pubs_folder . "/$file") && $file[0] != '.') {
                    $json_data = file_get_contents($pubs_folder . "/$file");
                    $this->publications[str_replace('.json', '', $file)] = json_decode($json_data);
                    if(JSON_ERROR_NONE !== json_last_error()) {
                        throw new Exception("json error");
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers academicpuma\citeproc\php\CiteProc::getInstance
     * @todo   Implement testGetInstance().
     */
    public function testGetInstance() {
        
        $this->markTestSkipped();
    }

    /**
     * @covers academicpuma\citeproc\php\CiteProc::init
     * @todo   Implement testInit().
     */
    public function testInit() {
        
        $this->markTestSkipped();
    }

    /**
     * @covers academicpuma\citeproc\php\CiteProc::render
     * @todo   Implement testRender().
     */
    public function testRender() {
        foreach($this->publications as $key => $pub) {
            
            foreach(CSLUtils::$styles as $styleName) {
                $cslFilename = dirname('..').CSLUtils::STYLES_FOLDER.$styleName.".csl";
                
                $csl = file_get_contents($cslFilename);
                $citeProc = new CiteProc($csl);

                $actual = preg_replace("!(\s{2,})!","",strip_tags($citeProc->render($pub)));
                
                echo "\n$key in $styleName:\n$actual\n";
                
                //$expected = file_get_contents($key.'_'.$styleName.'.html');
                //$this->assertSame("", $actual);
            }
        }
    }
}

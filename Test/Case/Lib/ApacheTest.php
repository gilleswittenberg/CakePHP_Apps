<?php
App::uses('ApacheLib', 'Apps.Lib');
App::uses('File', 'Utility');

class ApacheTest extends CakeTestCase {

    public function setUp() {
        parent::setUp();
        $this->Apache = new ApacheLib();
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->Apache);
    }

    public function testGetDirectiveContent() {
		$expected = "<VirtualHost *:80>\nServerName www.example.com\nDocumentRoot /var/www\n</VirtualHost>";
        $return = $this->Apache->writeDirective('www.example.com', '/var/www');
        $this->assertEquals($return['content'], $expected);
		// clean up
		$file = new File($return['filename']);
		$file->delete();
    }

    public function testDirectiveContentServerAliases() {
		$expected = "<VirtualHost *:80>\nServerName www.example.com\nServerAlias www2.example.com\nServerAlias www.example2.com\nDocumentRoot /var/www\n</VirtualHost>";
        $return = $this->Apache->writeDirective('www.example.com', '/var/www', array(array('domain' => 'www2.example.com'), array('domain' => 'www.example2.com')));
        $this->assertEquals($expected, $return['content']);
		// clean up
		$file = new File($return['filename']);
		$file->delete();
    }

    public function testWrite() {
        $return = $this->Apache->writeDirective('www.example.com', '/var/www');
        $this->assertEquals($return['filename'], '/etc/apache2/sites-available/www.example.com');
		$file = new File('/etc/apache2/sites-available/www.example.com');
		$this->assertTrue($file->exists());
		// clean up
		$file->delete();
	}

	public function testDelete() {
        $return = $this->Apache->writeDirective('www.example.com', '/var/www');
		$file = new File('/etc/apache2/sites-available/www.example.com');
		$this->assertTrue($file->exists());
		$this->Apache->deleteDirective('www.example.com');
		$this->assertFalse($file->exists());
	}
}

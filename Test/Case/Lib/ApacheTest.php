<?php
App::uses('ApacheLib', 'Apps.Lib');
App::uses('File', 'Utility');

class ApacheTest extends CakeTestCase {

    public function setUp() {
        parent::setUp();
        $this->Apache = new ApacheLib();
    }

    public function testGetDomainOneDomain() {
		$expected = "<VirtualHost *:80>\nServerName www.example.com\nDocumentRoot /var/www\n</VirtualHost>";
        $return = $this->Apache->writeDirective('www.example.com', '/var/www');
        $this->assertEquals($return['content'], $expected);
    }

    public function testGetDomainMultipleDomain() {
		$expected = "<VirtualHost *:80>\nServerName www.example.com\nServerAlias www2.example.com\nDocumentRoot /var/www\n</VirtualHost>";
        $return = $this->Apache->writeDirective('www.example.com', '/var/www', array(array('domain' => 'www2.example.com')));
        $this->assertEquals($expected, $return['content']);
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

    public function tearDown() {
        parent::tearDown();
        unset($this->Apache);
    }
}

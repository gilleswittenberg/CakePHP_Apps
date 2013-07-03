<?php
/**
 * ApplicationFixture
 *
 */
class ApplicationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99, 'key' => 'primary'),
		'document_root_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99),
		'server_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 99, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'document_root_id' => 1,
			'server_name' => 'application-1.example.com',
			'slug' => 'Lorem ipsum dolor sit amet',
			'status' => 'Lorem ipsum dolor sit amet',
			'modified' => '2013-06-13 15:26:15',
			'created' => '2013-06-13 15:26:15'
		),
	);

}

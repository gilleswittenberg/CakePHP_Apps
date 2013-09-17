<?php
/**
 * DatabaseFixture
 *
 */
class DatabaseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99, 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99),
		'host' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'database' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'login' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'application_id' => 1,
			'host' => 'localhost',
			'database' => 'database',
			'login' => 'username',
			'password' => 'abcdefgh123',
			'modified' => '2013-06-13 15:29:50',
			'created' => '2013-06-13 15:29:50'
		),
	);

}

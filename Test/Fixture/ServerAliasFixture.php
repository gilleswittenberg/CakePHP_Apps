<?php
/**
 * ServerAliasFixture
 *
 */
class ServerAliasFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99, 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99),
		'domain' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'domain' => 'www.example1.com',
			'modified' => '2013-06-21 19:41:39',
			'created' => '2013-06-21 19:41:39'
		),
		array(
			'id' => 2,
			'application_id' => 1,
			'domain' => 'subdomain.example1.com',
			'modified' => '2013-06-21 19:41:39',
			'created' => '2013-06-21 19:41:39'
		)
	);

}

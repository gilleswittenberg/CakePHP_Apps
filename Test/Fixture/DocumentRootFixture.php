<?php
/**
 * DocumentRootFixture
 *
 */
class DocumentRootFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 99, 'key' => 'primary'),
		'absolute_path' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 199, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'app_dir' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 49, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'absolute_path' => array('column' => 'absolute_path', 'unique' => 1)
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
			'absolute_path' => APP,
			'app_dir' => APP_DIR,
			'modified' => '2013-06-13 15:53:57',
			'created' => '2013-06-13 15:53:57'
		),
	);

}

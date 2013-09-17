<?php
App::uses('AppsAppModel', 'Apps.Model');
App::uses('ApacheLib', 'Apps.Lib');
/**
 * ServerAlias Model
 *
 * @property Application $Application
 */
class ServerAlias extends AppsAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'domain';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'domain' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Supply a valid domain'
			),
			'domain' => array(
				'rule' => array('validDomain'),
				'message' => 'Supply a valid domain'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'ServerAlias already exists'
			)
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Application' => array(
			'className' => 'Apps.Application',
		)
	);

	public $prevServerAlias;

	public function add() {
		$this->recursive = 2;
		$serverAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $this->id)));
		$this->Application->linkConfig($serverAlias['Application']['DocumentRoot']['absolute_path'] . DS . $serverAlias['Application']['DocumentRoot']['app_dir'], $serverAlias['Application']['server_name'], $serverAlias['ServerAlias']['domain']);
		$this->Application->rewriteServerAliases($serverAlias['Application']['id']);
	}

	public function beforeDelete($cascade = true) {
		$this->recursive = 2;
		$this->prevServerAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $this->id)));
		return true;
	}

	public function afterDelete() {
		$this->Application->unlinkConfig($this->prevServerAlias['Application']['DocumentRoot']['absolute_path'] . DS . $this->prevServerAlias['Application']['DocumentRoot']['app_dir'], $this->prevServerAlias['ServerAlias']['domain']);
	}
}

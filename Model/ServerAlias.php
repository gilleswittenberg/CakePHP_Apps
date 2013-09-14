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
			),
			'unique' => array(
				'rule' => 'isUnique'
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

	public function add($id) {
		$serverAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $id)));
		$this->Application->linkConfig($serverAlias['Application']['DocumentRoot']['absolute_path'], $serverAlias['Appliction']['server_name'], $serverAlias['ServerAlias']['domain']);
		$this->Application->apacheWriteDirective($serverAlias['Application']['id']);
		$this->Application->restartApache();
	}

	public function beforeDelete($cascade = true) {
		$this->recursive = 2;
		$this->prevServerAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $this->id)));
		return true;
	}

	public function afterDelete($cascade = true) {
		$this->Application->unlinkConfig($this->prevServerAlias['Application']['DocumentRoot']['absolute_path'], $this->prevServerAlias['ServerAlias']['domain']);
		$this->Application->apacheWriteDirective($this->prevServerAlias['Application']['id']);
		$this->Application->restartApache();
	}
}

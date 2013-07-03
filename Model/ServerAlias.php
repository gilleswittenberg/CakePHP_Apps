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

	public function beforeSave() {
		if ($this->exists()) {
			$serverAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $this->id)));
			$this->prevDomain = $serverAlias['ServerAlias']['domain'];
		}
	}

	public function afterSave($created) {
		$serverAlias = $this->find('first', array('recursive' => 2, 'conditions' => array('ServerAlias.id' => $this->id)));
		if (!$created && $this->data['ServerAlias']['domain'] !== $this->prevDomain) {
			$this->Application->unlink($serverAlias['Application']['DocumentRoot']['absolute_path'], $this->prevDomain);
		}
		$apacheLib = new ApacheLib();
		$apacheLib->writeDirective($serverAlias['Application']['server_name'], $serverAlias['Application']['DocumentRoot']['absolute_path'], $serverAlias['ServerAlias']);
		$this->Application->linkConfig($serverAlias['Application']['DocumentRoot']['absolute_path'], $serverAlias['Application']['server_name'], $serverAlias['ServerAlias']['domain']);
	}

	public function beforeDelete() {
		$this->recursive = 2;
		$serverAlias = $this->find('first', array('conditions' => array('ServerAlias.id' => $this->id)));
		$this->prevServerAlias = $serverAlias;
		return true;
	}

	public function afterDelete() {
		$this->Application->unlinkConfig($this->prevServerAlias['Application']['DocumentRoot']['absolute_path'], $this->prevServerAlias['ServerAlias']['domain']);
	}
}

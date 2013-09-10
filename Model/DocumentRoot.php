<?php
App::uses('AppsAppModel', 'Apps.Model');
/**
 * DocumentRoot Model
 *
 * @property Application $Application
 */
class DocumentRoot extends AppsAppModel {

	public $displayField = 'absolute_path';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'absolute_path' => array(
			'absolutePath' => array(
				'rule' => '#^/[a-zA-Z0-9/_\-\.]+$#',
				'message' => 'Supply an absolute path'
			),
		),
		'app_dir' => array(
			'app_dir' => array(
				'rule' => '#^[a-zA-Z0-9_\-\.]+$#',
				'message' => 'No valid dirname'
			)
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Application' => array(
			'className' => 'Apps.Application',
		)
	);

	public function beforeValidate() {
		if (!empty($this->data['DocumentRoot']['absolute_path'])) {
			$this->data['DocumentRoot']['absolute_path'] = rtrim($this->data['DocumentRoot']['absolute_path'], '/');
		}
		if (!empty($this->data['DocumentRoot']['app_dir'])) {
			$this->data['DocumentRoot']['app_dir'] = trim($this->data['DocumentRoot']['app_dir'], '/');
		}
		return true;
	}

	public function beforeDelete($cascade = true) {
		$documentRoot = $this->find('first', array('conditions' => array('id' => $this->id)));
		if (!empty($documentRoot['Application'])) {
			return false;
		}
		return true;
	}
}

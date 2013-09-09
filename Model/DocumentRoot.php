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
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
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

	public function beforeDelete($cascade = true) {
		$documentRoot = $this->find('first', array('conditions' => array('id' => $this->id)));
		if (!empty($documentRoot['Application'])) {
			return false;
		}
		return true;
	}
}

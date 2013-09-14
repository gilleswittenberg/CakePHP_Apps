<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Plugin.Apps.Model
 */
class AppsAppModel extends AppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		$dbConfig = Configure::read('Apps.dbConfig');
		if ($dbConfig && $dbConfig !== 'default') {
			$this->useDbConfig = $dbConfig;
		}
		parent::__construct($id, $table, $ds);
	}

	// http://stackoverflow.com/questions/1755144/how-to-validate-domain-name-in-php/4694816#4694816
	public function validDomain($check) {
		$keys = array_keys($check);
		$domain = $check[$keys[0]];
		return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain) //valid chars check
			&& preg_match("/^.{1,253}$/", $domain) //overall length check
			&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain)   ); //length of each label
	}
}

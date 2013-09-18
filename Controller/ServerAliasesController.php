<?php
App::uses('AppsAppController', 'Apps.Controller');
/**
 * ServerAliases Controller
 *
 * @property ServerAlias $ServerAlias
 */
class ServerAliasesController extends AppsAppController {

/**
 * add method
 *
 * @return void
 */
	public function add($applicationId) {
		if ($this->request->is('post')) {
			$this->ServerAlias->create();
			if ($this->ServerAlias->save($this->request->data)) {
				$this->ServerAlias->add();
				$this->Session->setFlash(__('The server alias has been added'));
				$this->redirect(array('controller' => 'applications', 'action' => 'view', $applicationId));
			} else {
				$this->Session->setFlash(__('The server alias could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data['ServerAlias']['application_id'] = $applicationId;
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ServerAlias->id = $id;
		if (!$this->ServerAlias->exists()) {
			throw new NotFoundException(__('Invalid server alias'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ServerAlias->delete()) {
			$this->ServerAlias->Application->rewriteServerAliases($this->prevServerAlias['Application']['id']);
			$this->Session->setFlash(__('Server alias deleted'));
			$this->redirect(array('controller' => 'application', 'action' => 'view', $this->prevServerAlias['Application']['id']));
		}
		$this->Session->setFlash(__('Server alias was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

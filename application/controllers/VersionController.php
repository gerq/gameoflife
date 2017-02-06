<?php

class VersionController extends My_Base
{

	public function init()
  {

			parent::init();

			$version = $this->_bootstrap->getOption('version');
			//$this->_helper->viewRenderer->setNeverRender();
			$this->view->success = "true";
			$this->view->version = $version['major'] . '.' . $version['minor'];
	}

  public function indexAction()
  {

	}

}

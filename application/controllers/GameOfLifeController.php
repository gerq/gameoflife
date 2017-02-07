<?php

// REST API
class GameoflifeController extends My_Base
{

	const STATE_LAST = 'last';
	const STATE_NEXT = 'next';

	private $_game = false;

	public function init()
  {
			parent::init();
	}

	// get table data by json
	public function indexAction()
  {
		$state = $this->_getParam('state', self::STATE_LAST);
		$w = ((int)$this->_getParam('w', 20)) ? (int)abs($this->_getParam('w', 20)) : 20;
		$h = (int)$this->_getParam('h', 20) ? (int)abs($this->_getParam('h', 20)) : 20;
		$iteration = $this->_getParam('iteration', 1);
		$extra = $this->_getParam('extra');
		$template = $this->_getParam('template');

		$opts = array(
			'width' => $w,
			'height' => $h,
			'random' => false,
			'template' => $template
			//'template' => 'bouncer'
		);
		$this->_game = new My_Game($opts);

		$extraPoints = json_decode($extra);
		$this->_game->addExtraPoints($extraPoints);

		$this->getResponse()->setHttpResponseCode(My_Base::OK);
		if($state == self::STATE_NEXT) {
			//if(is_readable($this->_getCacheFile()) && $cachedFile = file_get_contents($this->_getCacheFile())) {
				//$cacheData = unserialize($cachedFile);
				//$this->_game->setTable($cacheData);
			//}
			for($i=0; $i<$iteration; $i++) {
				$this->_game->newGeneration();
			}

		}
		$this->view->result = $this->_game->getTable();
		$this->view->success = 'true';

		$serializedData = serialize($this->_game->getTable());
		file_put_contents($this->_getCacheFile(), $serializedData);

		// for debug
		// $this->_game->render();
		// die('xxx');
	}

	// get cache files path, now its unused
	protected function _getCacheFile() {
		return APPLICATION_PATH . '/../templates/cache/cache.txt';
	}

}

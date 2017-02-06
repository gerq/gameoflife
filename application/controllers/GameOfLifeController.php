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

			$opts = array(
        'width' => 20,
        'height' => 10,
        'random' => false,
        'template' => '10cell'
      );

			$this->_game = new My_Game($opts);
	}

	public function indexAction()
  {
		$state = $this->_getParam('state', self::STATE_LAST);

		$this->getResponse()->setHttpResponseCode(My_Base::OK);
		if($state == self::STATE_NEXT) {
			if(is_readable($this->_getCacheFile()) && $cachedFile = file_get_contents($this->_getCacheFile())) {
				$cacheData = unserialize($cachedFile);
				$this->_game->setTable($cacheData);
			}
			$this->_game->newGeneration();
		}
		$this->view->result = $this->_game->getTable();
		$this->view->success = 'true';

		$serializedData = serialize($this->_game->getTable());
		file_put_contents($this->_getCacheFile(), $serializedData);

		// for debug
		// $this->_game->render();
		// die('xxx');
	}

	protected function _getCacheFile() {
		return APPLICATION_PATH . '/../templates/cache/cache.txt';
	}

}

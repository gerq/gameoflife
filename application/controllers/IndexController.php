<?php

// Normal controller
class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        //$db = $bootstrap->getResource('db');

        $options = $bootstrap->getOption('resources');
        /*
		$dbFile  = $options['db']['params']['dbname'];
        if (file_exists($dbFile)) {
            unlink($dbFile);
        }
		*/

      // for debug
      /*

      $opts = array(
        'width' => 20,
        'height' => 10,
        'random' => false,
        'template' => '10cell'
      );

      $game = new My_Game($opts);

      $game->render();
      $game->newGeneration();

      $game->render();
      $game->newGeneration();

      $game->render();
      $game->newGeneration();

      $game->render();
      */

    }

}

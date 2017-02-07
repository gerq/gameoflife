<?php

// base class for REST API
class My_Base extends Zend_Rest_Controller
{

	// default status codes
	const OK = 200;
	const BAD_REQUEST = 400;
	const NOT_FOUND = 404;
	const METHOD_NOT_ALLOWED = 405;
	const INTERNAL_SERVER_ERROR = 500;

	protected $_form;

	protected $_bootstrap;

	// defaut json answare
	public function init()
  {
        $this->_bootstrap = $this->getInvokeArg('bootstrap');

				$options = $this->_bootstrap->getOption('resources');

				$context = $this->_bootstrap->getOption('context');
				$contextSwitch = $this->_helper->getHelper('contextSwitch');
				$contextSwitch->addActionContext('index', array('xml','json'))->initContext($context['default']);
				$contextSwitch->addActionContext('get', array('xml','json'))->initContext($context['default']);
				$contextSwitch->addActionContext('put', array('xml','json'))->initContext($context['default']);
				$contextSwitch->addActionContext('delete', array('xml','json'))->initContext($context['default']);
				//$contextSwitch->addActionContext('index', array('xml','json'))->initContext();

				$this->view->method = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	}

    /**
     * index
     */
    public function indexAction()
    {
			$this->methodNotAllowed();
			//print $this->_helper->getParam('abc');
		}

 		/**
     * list
     */
    public function listAction()
    {
        $this->methodNotAllowed();
    }

    /**
     * get by id
     */
    public function getAction()
    {
				$this->methodNotAllowed();
		}

		/**
     * add
     */
    public function newAction() {
				$this->methodNotAllowed();
		}

    /**
     * post
     */
    public function postAction() {
				$this->methodNotAllowed();
		}

 		/**
     * modify
     */
    public function editAction() {
				$this->methodNotAllowed();
		}

    /**
     * modify by id
     */
    public function putAction() {
				$this->methodNotAllowed();
    }

    /**
     * delete by id
     */
    public function deleteAction() {
				$this->methodNotAllowed();
		}

		// head
		public function headAction() {
				$this->methodNotAllowed();
		}

		// not implemented
		protected function methodNotAllowed() {
			$this->getResponse()->setHttpResponseCode(self::METHOD_NOT_ALLOWED);
			$this->view->success = false;
			$this->view->message = 'Method not allowed';
		}

		// *.dev = develpment environment
		public static function isDevelopment() {
			$return = false;
			if(preg_match('/.*\.dev/i', $_SERVER['HTTP_HOST'])) {
				return true;
			}
		}
}

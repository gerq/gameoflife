<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(My_Base::NOT_FOUND);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error;
                $response = My_Base::INTERNAL_SERVER_ERROR;
                if(My_Base::INTERNAL_SERVER_ERROR != $this->getResponse()->getHttpResponseCode()) {
                  $response = $this->getResponse()->getHttpResponseCode();
                }
                $this->getResponse()->setHttpResponseCode($response);
                $this->view->success = 'false';

                if(My_Base::isDevelopment()) {
                  $this->view->message = 'Application error<br>' . $errors->exception;
                } else {
                  $this->view->message = 'Application error';
                }
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }


}

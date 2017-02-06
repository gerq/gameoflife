<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

    // Define path to library directory
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../lib/vendor/zendframework/zendframework1/library'));

defined('PHPFLICKR_PATH')
    || define('PHPFLICKR_PATH', realpath(dirname(__FILE__) . '/../lib/vendor/wikia/phpflickr'));

// Define application environment, see in htaccess
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//var_dump(LIBRARY_PATH);
//die();

// use Zend library
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    realpath(LIBRARY_PATH),
    realpath(PHPFLICKR_PATH),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();

<?php
define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', substr(windows_path_fix(DOC_ROOT), strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/');
define('URL_ROOT_TRIM', ltrim(URL_ROOT,"\\"));

echo URL_ROOT;

error_reporting(E_STRICT | E_ALL);
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');

//fTimestamp::setDefaultTimezone('America/New_York');

fAuthorization::setLoginPage("/" . URL_ROOT_TRIM . 'authentication.php');
fAuthorization::setAuthLevels(
    array(
        'admin' => 100,
        'user'  => 50,
        'guest' => 25
    )
);


// This prevents cross-site session transfer
fSession::setPath(DOC_ROOT . '/session/');


include DOC_ROOT . '/resources/library/flourish/constructor_functions.php';

/**
 * Configuration Settings
 *
 */
 
 $config = array(
	"db" => array(
		"dbName" => "umw_mms",
		"dbHost" => "localhost",
		"dbUsername" => "useradmin",
		"password" => "test0",
		),
	"title" => "Material Management System",
	"version" => "v0.75a"
	);

/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class  Name of the class to load
 * @return void
 */
function __autoload($class)
{
	$flourish_file = DOC_ROOT . '/resources/library/flourish/' . $class . '.php';
 
	if (file_exists($flourish_file)) {
		return require $flourish_file;
	}
	
	$file = DOC_ROOT . '/classes/' . $class . '.php';
 
	if (file_exists($file)) {
		return require $file;
	}
	
	throw new Exception('The class ' . $class . ' could not be loaded');
}

function windows_path_fix($path)
{
    // is this running on a Windows machine
    // look at $_ENV['OS']
    if ( isset($_ENV['OS']) && preg_match('/window/i', $_ENV['OS']) )
    {
        // if Windows...
        // convert forward slashes '/' to back slashes '\'
        $path = preg_replace('/\//', '\\', $path);
    }

    return $path;
}
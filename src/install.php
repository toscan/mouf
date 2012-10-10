<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 
/**
 * This file is used to install the Mouf framework by creating the .htaccess file.
 */

$uri = $_SERVER["REQUEST_URI"];

$installPos = strpos($uri, "/src/install.php");
if ($installPos !== FALSE) {
	$uri = substr($uri, 0, $installPos);
	$uriWithoutMouf = substr($uri, 0, -16);
}


$str = "Options FollowSymLinks -MultiViews
RewriteEngine on
RewriteBase $uri

RewriteCond %{REQUEST_FILENAME} !-f
#RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule mouf\\/doc\\/view\\/ vendor/mouf/mvc.splash/src/splash.php
RewriteRule !((\\.(js|ico|gif|jpg|png|css)$)|^vendor|^src/direct/) vendor/mouf/mvc.splash/src/splash.php";

file_put_contents("../.htaccess", $str);
chmod("../.htaccess", 0664);

// Now, let's write the basic Mouf files:
if (!file_exists("../../../../mouf")) {
	mkdir("../../../../mouf", 0775);
}


// Write Mouf.php:
if (!file_exists("../../../../mouf/Mouf.php")) {
	$moufStr = "<?php
require_once __DIR__.'/../vendor/autoload.php';

require_once 'MoufComponents.php';

define('ROOT_PATH', realpath(__DIR__.'/..').DIRECTORY_SEPARATOR);

require_once __DIR__.'/../config.php';

define('MOUF_URL', ROOT_URL.'vendor/mouf/mouf/');
?>";
	
	file_put_contents("../../../../mouf/Mouf.php", $moufStr);
	chmod("../../../../mouf/Mouf.php", 0664);
}



// Write MoufComponents.php:
if (!file_exists("../../../../mouf/MoufComponents.php")) {
	$moufComponentsStr = "<?php
/**
 * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.
 */

use Mouf\MoufManager;
MoufManager::initMoufManager();
\$moufManager = MoufManager::getMoufManager();

\$moufManager->getConfigManager()->setConstantsDefinitionArray(array (
  'ROOT_URL' => 
  array (
    'defaultValue' => '',
    'type' => 'string',
    'comment' => 'The ROOT_URL of the Mouf web application. This is the URL, starting and ending with a slash, that you use to access the Mouf administration interface.',
  ),
));

?>";
	
	file_put_contents("../../../../mouf/MoufComponents.php", $moufComponentsStr);
	chmod("../../../../mouf/MoufComponents.php", 0664);
}

// Finally, let's generate the MoufUI.php file:
if (!file_exists("../../../../mouf/MoufUI.php")) {
	$moufUIStr = "<?php
/**
 * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.
 */
	
	?>";
	
	file_put_contents("../../../../mouf/MoufUI.php", $moufUIStr);
	chmod("../../../../mouf/MoufUI.php", 0664);
}

// Finally 2, let's generate the config.php file:
if (!file_exists("../../../../config.php")) {
	$moufConfig = "<?php
/**
 * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.
 * Use the UI to edit it instead.
 */

/**
 * The ROOT_URL of the Mouf web application. This is the URL, starting and ending with a slash, that you use to access the Mouf administration interface.
 */
define('ROOT_URL', '".$uriWithoutMouf."');
?>";
	
	file_put_contents("../../../../config.php", $moufConfig);
	chmod("../../../../config.php", 0664);
}

// Finally 3 :), let's generate the MoufUsers.php file:
if (!file_exists("../../../../mouf/MoufUsers.php")) {
	$moufConfig = "<?php
/**
 * This contains the users allowed to access the Mouf framework.
 */
\$users[".var_export(install_userinput_to_plainstring($_REQUEST['login']), true)."] = array('password'=>".var_export(sha1(install_userinput_to_plainstring($_REQUEST['password'])), true).", 'options'=>null);
	
	?>";
	
	file_put_contents("../../../../mouf/MoufUsers.php", $moufConfig);
	chmod("../../../../mouf/MoufUsers.php", 0664);
}

// Finally 4, let's generate the config.php file for the mouf directory:
if (!file_exists("../config.php")) {
	$moufConfig = "<?php
/**
 * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.
 * Use the UI to edit it instead.
 */

/**
 * The ROOT_URL of the Mouf web application. This is the URL, starting and ending with a slash, that you use to access the Mouf administration interface.
 */
define('ROOT_URL', '".$uri."/');
 
	?>";
	
	file_put_contents("../config.php", $moufConfig);
	chmod("../config.php", 0664);
}

function install_userinput_to_plainstring($str) {
	if (get_magic_quotes_gpc()==1)
	{
		$str = stripslashes($str);
		// Rajouter les slashes soumis par l'utilisateur
		//$str = str_replace('\\', '\\\\', $str);
		return $str;
	}
	else
		return $str;
}


header("Location: ".$uri."/");

?>
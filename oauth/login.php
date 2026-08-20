<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

if(!file_exists('../vendor/autoload.php')){
    die('vendor/autoload.php not found');
}

require '../vendor/autoload.php';

if(!class_exists('Hybridauth\\Hybridauth')){
    die('HybridAuth library not installed');
}

$config = require 'config.php';

$provider = $_GET['provider'] ?? '';

if(empty($provider)){
    die('Provider not specified');
}

$hybridauth = new Hybridauth\Hybridauth($config);

$adapter = $hybridauth->authenticate($provider);

$profile = $adapter->getUserProfile();

$_SESSION['oauth_profile'] = $profile;

header("Location: callback.php");
exit;
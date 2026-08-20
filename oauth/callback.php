<?php

session_start();

require "../database.php";

$profile =
$_SESSION['oauth_profile'];

$email =
$profile->email;

$name =
$profile->displayName;

$oauth_id =
$profile->identifier;

$provider =
$profile->providerName;
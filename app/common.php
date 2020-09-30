<?php

$global   = __DIR__.'/Config/global.php';
$database = __DIR__.'/Config/database.php';
$language = __DIR__.'/Config/language.php';
$email    = __DIR__.'/Config/email.php';
$appglobal   = __DIR__ . '/../../../../app/Config/global.php';
$appdatabase = __DIR__ . '/../../../../app/Config/database.php';
$applanguage = __DIR__ . '/../../../../app/Config/language.php';
$appemail    = __DIR__ . '/../../../../app/Config/email.php';
$approutes   = __DIR__ . '/../../../../app/Config/routes.php';

if (file_exists($appglobal)){
	require $appglobal;
}
if (file_exists($global)){
    require $global;
}
if (file_exists($appdatabase)){
	require $appdatabase;
}
if (file_exists($database)){
    require $database;
}
if (file_exists($applanguage)){
    require $applanguage;
}
if (file_exists($language)){
    require $language;
}
if (file_exists($appemail)){
    require $appemail;
}
if (file_exists($email)){
    require $email;
}
if (file_exists($approutes)){
	require $approutes;
}

if (DEBUG_MODE == true){
    error_reporting(1);
    ini_set("display_errors", "1");
} else {
    error_reporting(0);
    ini_set("display_errors", "0");
}

date_default_timezone_set(APP_TIMEZONE);

$classes = array(
    'Core' => array(
        'Http',
        'Layout',
    ),
    'Helper' => array(
        'Common',
        'Curl',
    ),
);
<?php

date_default_timezone_set('Asia/Jakarta');

$global   = __DIR__.'/Config/global.php';
$database = __DIR__.'/Config/database.php';
$language = __DIR__.'/Config/language.php';
$appglobal   = 'app/Config/global.php';
$appdatabase = 'app/Config/database.php';
$applanguage = 'app/Config/language.php';

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
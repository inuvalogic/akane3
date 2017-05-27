<?php

date_default_timezone_set('Asia/Jakarta');

$global   = __DIR__.'/Config/global.php';
$database = __DIR__.'/Config/database.php';
$language = __DIR__.'/Config/language.php';

if (file_exists($global)){
	require $global;
}
if (file_exists($database)){
	require $database;
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
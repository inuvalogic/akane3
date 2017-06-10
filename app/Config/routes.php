<?php
/**
 * mapping url disini
 * 
 * format:
 * 'slug-url' => 'controller:action'
 * 
 * slug url support regex contoh:
 * 'article/([0-9]+)/(.*)' => 'article:detail'
 * 
 */

$routes = [
	'about' => 'page:about',
	'artikel' => 'artikel:index'
];
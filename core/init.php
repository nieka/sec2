<?php
session_start();
$GLOBALS['config'] = array(
	'location' => array(
        'url' => 'localhost:7000',
        'dir' => $_SERVER['DOCUMENT_ROOT'] . ''
    ),
	'mysql'		=> array(
		'host'		=> 'databases.aii.avans.nl',
		'username'	=> 'nfjhoffm',
		'password'	=> 'Ab12345',
		'db'		=> 'nfjhoffm_db2'),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => '604800'),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'));

spl_autoload_register(function($class) {
	require_once $_SERVER['DOCUMENT_ROOT'] .'/classes/'.$class.'.php';
});
?>

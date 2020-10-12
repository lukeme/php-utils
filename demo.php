<?php

require_once __DIR__ . '/vendor/autoload.php';

use Lukeme\Utils\Db;

$conf = [
	'driver'=>'mysql',
	'host'=>'localhost',
	'port'=>'3306',
	'name'=>'test',
	'user'=>'root',
	'pass'=>'phpts',
	'char'=>'utf8',
];

$db = new Db($conf);

$row = $db->fetchOne("select * from posts");

dump($row, range(1,3));

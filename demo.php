<?php

require_once __DIR__ . '/vendor/autoload.php';

use Lukeme\Utils\Db;

$conf = [
    'driver' => 'mysql',
    'host' => '192.168.0.4',
    'port' => '3306',
    'name' => 'test',
    'user' => 'root',
    'pass' => 'phpts',
    'char' => 'utf8',
];

$db = new Db($conf);

$row = $db->fetchOne("select * from posts limit 1");
dump($db->getSql('posts', $row));
dump($db->getSql('posts', $row, [], 'pgsql'));
dump($row);

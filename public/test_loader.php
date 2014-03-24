<?php

include '../bootstrap.php';

$loader = new Dark\Core\Db\DbLoader(__DIR__ . '/loading.csv', 'mytable');
echo '<pre>', $loader, '</pre>';


Dark\Core\Db\DbPool::add(
	Dark\Core\Db\DbConnector::create(
		(array) Config::create()->db
	)
);

$db = \Dark\Core\Db\DbPool::get();
$db->query($loader);